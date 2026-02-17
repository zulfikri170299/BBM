<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Satker;
use App\Models\RiwayatTopup;
use App\Imports\TopupSaldoImport;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KendaraanExport;
use App\Exports\LaporanBulananExport;
use App\Models\User;
use App\Notifications\TopupNotification;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::with('satker')->latest();

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        $kendaraans = $query->paginate(15)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();
        $allKendaraans = Kendaraan::select('id', 'satker_id', 'no_polisi', 'jenis_kendaraan', 'jenis_bbm', 'saldo')->get();
        $adminStocks = \App\Models\AdminBbmStock::all();

        return view('admin.kendaraans.index', compact('kendaraans', 'satkers', 'allKendaraans', 'adminStocks'));
    }

    public function create()
    {
        $satkers = Satker::orderBy('nama_satker')->get();
        return view('admin.kendaraans.create', compact('satkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'no_polisi' => 'required|string|max:20|unique:kendaraans',
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
        ]);

        $barcode = strtoupper(\Illuminate\Support\Str::random(10));
        while (Kendaraan::where('barcode', $barcode)->exists()) {
            $barcode = strtoupper(\Illuminate\Support\Str::random(10));
        }

        $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Generate kode kendaraan otomatis: KND-00001
        $lastId = Kendaraan::max('id') ?? 0;
        $kodeKendaraan = 'KND-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        Kendaraan::create([
            'satker_id' => $request->satker_id,
            'kode_kendaraan' => $kodeKendaraan,
            'no_polisi' => $request->no_polisi,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'jenis_bbm' => $request->jenis_bbm,
            'barcode' => $barcode,
            'pin' => $pin,
            'saldo' => 0,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan Kendaraan Baru (Super Admin): {$request->no_polisi} pada Satker " . Satker::find($request->satker_id, ['*'])->nama_satker
        ]);

        return redirect()->route('admin.kendaraans.index')->with('success', 'Kendaraan berhasil ditambahkan! Kode: ' . $kodeKendaraan . ' | Barcode: ' . $barcode . ' | PIN: ' . $pin . ' (Simpan PIN ini!)');
    }

    public function print(Kendaraan $kendaraan)
    {
        return view('satker.kendaraans.print', compact('kendaraan'));
    }

    public function sendOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $targetEmail = $user->otp_email ?: $user->email;

        try {
            $otp = $otpService->generateOtp($user->id);
            if ($otpService->sendOtp($targetEmail, $otp)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kode OTP berhasil dikirim ke Email Anda (' . $targetEmail . ').'
                ]);
            } else {
                throw new \Exception("Gagal mengirim email.");
            }
        } catch (\Exception $e) {
            Log::error("OTP Key Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim OTP. Cek log server.'
            ], 500);
        }
    }

    public function topup(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'jumlah' => 'required|numeric|min:1',
            'topup_password' => 'required|string',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        $user = auth()->user();

        // 0. Validasi Password Top Up
        if (! $user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di menu Profil > Password Top Up.');
        }

        if (! \Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah! Transaksi dibatalkan.');
        }

        try {
            DB::beginTransaction();

            // 1. Cek Stok Admin
            $adminStock = \App\Models\AdminBbmStock::where('jenis_bbm', $kendaraan->jenis_bbm)->first();
            if (!$adminStock || $adminStock->saldo < $request->jumlah) {
                return back()->with('error', "Stok Pusat untuk {$kendaraan->jenis_bbm} tidak cukup. Tersedia: " . ($adminStock ? $adminStock->saldo : 0) . " L.");
            }

            // 2. Potong Stok Admin
            $adminStock->decrement('saldo', $request->jumlah);

            // 3. Catat Riwayat Stok Admin
            \App\Models\RiwayatStokAdmin::create([
                'user_id' => $user->id,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'jumlah' => $request->jumlah,
                'tipe' => 'keluar',
                'keterangan' => "Top-up manual untuk kendaraan {$kendaraan->no_polisi}",
            ]);

            // 4. Update Saldo Kendaraan
            $oldSaldo = $kendaraan->saldo;
            $kendaraan->increment('saldo', $request->jumlah);

            // 5. Catat Riwayat Topup
            RiwayatTopup::create([
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $request->jumlah,
                'saldo_sebelum' => $oldSaldo,
                'saldo_sesudah' => $kendaraan->saldo,
                'metode' => 'manual',
                'status' => 'success',
            ]);

            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Top-up manual Kendaraan: {$kendaraan->no_polisi} sebesar {$request->jumlah} L"
            ]);

            DB::commit();

            // 6. Kirim Notifikasi ke Admin Satker terkait
            $adminSatkers = User::where('satker_id', $kendaraan->satker_id)
                ->where('role', 'admin_satker')
                ->get();

            Log::info("Mengirim notifikasi top-up ke " . count($adminSatkers) . " admin satker for kendaraan {$kendaraan->no_polisi}");

            foreach ($adminSatkers as $admin) {
                $admin->notify(new TopupNotification([
                    'title' => 'Penerimaan Saldo BBM',
                    'message' => "Super Admin telah melakukan top-up saldo untuk kendaraan {$kendaraan->no_polisi}.",
                    'amount' => $request->jumlah,
                    'no_polisi' => $kendaraan->no_polisi,
                ]));
            }
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Topup Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat top up: ' . $e->getMessage());
        }

        return redirect()->route('admin.kendaraans.index')->with('success', 'Top Up berhasil!');
    }

    public function importTopup(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'topup_password' => 'required|string',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'topup_password.required' => 'Password Top Up wajib diisi.',
        ]);

        $user = auth()->user();

        // 0. Validasi Password Top Up
        if (! $user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di menu Profil > Password Top Up.');
        }

        if (! \Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah! Import dibatalkan.');
        }

        $import = new TopupSaldoImport();
        Excel::import($import, $request->file('file'));

        // Trigger BA Otomatis per Satker
        $baController = new \App\Http\Controllers\Admin\BaController();
        foreach ($import->satkerSummary as $satkerId => $totals) {
            $satker = Satker::find($satkerId);
            if ($satker) {
                $baController->automatedGenerate($satker, $totals, now()->month, now()->year);
            }
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Import Top-up Saldo via Excel: Berhasil memproses {$import->successCount} kendaraan"
        ]);

        $message = "Import selesai! {$import->successCount} kendaraan berhasil di top up.";

        if (count($import->errors) > 0) {
            $errorList = implode(' | ', array_slice($import->errors, 0, 5));
            $message .= " Terdapat " . count($import->errors) . " error: {$errorList}";
            if (count($import->errors) > 5) {
                $message .= " ... dan " . (count($import->errors) - 5) . " error lainnya.";
            }
        }

        return redirect()->route('admin.kendaraans.index')->with(
            $import->successCount > 0 ? 'success' : 'error',
            $message
        );
    }

    public function export()
    {
        return Excel::download(new KendaraanExport, 'data_kendaraan_' . date('Y-m-d_H-i') . '.xlsx');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Row 1 kosong, header di Row 2
        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'SATKER');
        $sheet->setCellValue('C2', 'KODE KENDARAAN');
        $sheet->setCellValue('D2', 'JENIS KENDARAAN');
        $sheet->setCellValue('E2', 'NOPOL');
        $sheet->setCellValue('F2', 'JENIS BBM');
        $sheet->setCellValue('G2', 'JUMLAH LITER');

        // Contoh data di Row 3
        $sheet->setCellValue('A3', 1);
        $sheet->setCellValue('B3', 'BIRO LOGISTIK');
        $sheet->setCellValue('C3', 'KND-00001');
        $sheet->setCellValue('D3', 'Mobil Dinas');
        $sheet->setCellValue('E3', 'AB 1234 CD');
        $sheet->setCellValue('F3', 'Pertamax');
        $sheet->setCellValue('G3', 50);

        // Bold header
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $fileName = 'template_topup_saldo.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'topup');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }


    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraans.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'no_polisi' => ['required', 'string', 'max:20', Rule::unique('kendaraans')->ignore($kendaraan->id)],
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
            'pin' => 'nullable|numeric|digits:6',
        ]);

        $data = $request->except('pin');
        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        $kendaraan->update($data);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Kendaraan (Super Admin): {$kendaraan->no_polisi}"
        ]);

        return redirect()->route('admin.kendaraans.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus Kendaraan (Super Admin): {$kendaraan->no_polisi}"
        ]);

        return redirect()->route('admin.kendaraans.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function laporanBulanan(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
        ]);

        $satkerId = $request->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $data['satker'] = Satker::findOrFail($satkerId);

        return view('admin.kendaraans.laporan-bulanan', $data);
    }

    public function printLaporanBulanan(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
        ]);

        $satkerId = $request->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $satker = Satker::findOrFail($satkerId);
        $data['satkerName'] = $satker->nama_satker;

        $pdf = Pdf::loadView('satker.kendaraans.laporan-bulanan-print', $data)
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-bulanan-bbm-' . $satker->nama_satker . '-' . $data['namaBulan'] . '-' . $tahun . '.pdf');
    }

    public function exportLaporanBulanan(Request $request)
    {
        $request->validate([
            'satker_id' => 'required|exists:satkers,id',
        ]);

        $satkerId = $request->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $satker = Satker::findOrFail($satkerId);
        $data['satkerName'] = $satker->nama_satker;

        $fileName = 'laporan-bulanan-bbm-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $satker->nama_satker) . '-' . $data['namaBulan'] . '-' . $tahun . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanBulananExport($data), $fileName);
    }

    private function buildLaporanBulananData($satkerId, $bulan, $tahun)
    {
        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satkerId)->orderBy('jenis_bbm')->orderBy('no_polisi')->get();

        $startDate = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        // Bulan sebelumnya
        $prevMonthEnd = $startDate->copy()->subDay()->endOfDay();
        
        $namaBulan = $startDate->translatedFormat('F');
        $namaBulanSebelumnya = $prevMonthEnd->translatedFormat('F');

        $rows = [];
        $summaryByBbm = [];

        foreach ($kendaraans as $kendaraan) {
            // Top Up bulan ini
            $topupBulanIni = \App\Models\RiwayatTopup::where('kendaraan_id', $kendaraan->id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('jumlah');

            // Total top up sampai akhir bulan lalu
            $totalTopupSampaiSebelumnya = \App\Models\RiwayatTopup::where('kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEnd)
                ->sum('jumlah');

            // Total pemakaian (transaksi) sampai akhir bulan lalu
            $totalPemakaianSampaiSebelumnya = \App\Models\TransaksiBbm::where('kendaraan_id', $kendaraan->id)
                ->where('tanggal', '<=', $prevMonthEnd)
                ->sum('liter');

            // Total transfer keluar sampai akhir bulan lalu
            $totalTransferKeluarSebelumnya = \App\Models\RiwayatTransferSaldoPersonel::where('kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEnd)
                ->sum('jumlah');

            // Sisa BBM bulan lalu = total top up - total pemakaian - total transfer keluar
            $sisaBulanLalu = $totalTopupSampaiSebelumnya - $totalPemakaianSampaiSebelumnya - $totalTransferKeluarSebelumnya;
            if ($sisaBulanLalu < 0) $sisaBulanLalu = 0;

            // NEW: Transfer keluar bulan ini
            $transferBulanIni = \App\Models\RiwayatTransferSaldoPersonel::where('kendaraan_id', $kendaraan->id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('jumlah');

            // Total BBM = Sisa bulan lalu + Top Up bulan ini (Sesuai pola Satker: Transfer tidak langsung mengurangi Total BBM di sini)
            $totalBbm = $sisaBulanLalu + $topupBulanIni;
            
            // Pemakaian per hari bulan ini
            $dailyUsage = [];
            $totalPemakaian = 0;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = \Carbon\Carbon::create($tahun, $bulan, $d);
                $usage = \App\Models\TransaksiBbm::where('kendaraan_id', $kendaraan->id)
                    ->whereDate('tanggal', $date)
                    ->sum('liter');
                $dailyUsage[$d] = $usage > 0 ? round($usage, 0) : null;
                $totalPemakaian += $usage;
            }

            // Total Pemakaian = Total Harian + Transfer (Transfer dianggap pemakaian)
            $totalPemakaian += $transferBulanIni;

            // Sisa BBM = Total BBM - Total Pemakaian
            $sisaBbm = $totalBbm - $totalPemakaian;

            $row = [
                'kode_kendaraan' => $kendaraan->kode_kendaraan ?? '-',
                'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
                'no_polisi' => $kendaraan->no_polisi,
                'jenis_bbm' => $kendaraan->jenis_bbm,
                'sisa_bulan_lalu' => round($sisaBulanLalu, 0),
                'topup_bulan_ini' => round($topupBulanIni, 0),
                'total_bbm' => round($totalBbm, 0),
                'transfer_bulan_ini' => round($transferBulanIni, 0), 
                'has_transfer' => $transferBulanIni > 0,
                'daily_usage' => $dailyUsage,
                'total_pemakaian' => round($totalPemakaian, 0),
                'sisa_bbm' => round($sisaBbm, 0),
            ];
            $rows[] = $row;

            // Summary per jenis BBM
            $bbm = $kendaraan->jenis_bbm;
            if (!isset($summaryByBbm[$bbm])) {
                $summaryByBbm[$bbm] = [
                    'sisa_bulan_lalu' => 0,
                    'topup_bulan_ini' => 0,
                    'total_bbm' => 0,
                    'transfer_bulan_ini' => 0,
                    'total_pemakaian' => 0,
                    'sisa_bbm' => 0,
                ];
            }
            $summaryByBbm[$bbm]['sisa_bulan_lalu'] += $row['sisa_bulan_lalu'];
            $summaryByBbm[$bbm]['topup_bulan_ini'] += $row['topup_bulan_ini'];
            $summaryByBbm[$bbm]['total_bbm'] += $row['total_bbm'];
            $summaryByBbm[$bbm]['transfer_bulan_ini'] += $row['transfer_bulan_ini'];
            $summaryByBbm[$bbm]['total_pemakaian'] += $row['total_pemakaian'];
            $summaryByBbm[$bbm]['sisa_bbm'] += $row['sisa_bbm'];
        }

        return compact('rows', 'summaryByBbm', 'daysInMonth', 'bulan', 'tahun', 'namaBulan', 'namaBulanSebelumnya', 'satkerId');
    }
}

