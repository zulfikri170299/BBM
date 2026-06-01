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


use App\Traits\PaginatesTables;

class KendaraanController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = Kendaraan::with('satker')->latest();

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                    ->orWhere('kode_kendaraan', 'like', "%{$search}%")
                    ->orWhere('jenis_kendaraan', 'like', "%{$search}%");
            });
        }

        $perPage = $this->getPerPage($request);
        $kendaraans = $query->paginate($perPage)->withQueryString();
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
            'jumlah' => 'required|numeric|min:0.1',
            'topup_password' => 'required|string',
            'tanggal_topup' => 'required|date',
        ], [
            'jumlah.required' => 'Jumlah top up wajib diisi.',
            'jumlah.numeric' => 'Jumlah top up harus berupa angka.',
            'jumlah.min' => 'Jumlah top up minimal 0.1 Liter.',
            'topup_password.required' => 'Password Top Up wajib diisi.',
            'tanggal_topup.required' => 'Tanggal Top Up wajib diisi.',
            'tanggal_topup.date' => 'Format Tanggal Top Up tidak valid.',
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
                'created_at' => \Carbon\Carbon::parse($request->tanggal_topup)->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::parse($request->tanggal_topup)->format('Y-m-d H:i:s'),
            ]);

            // 4. Update Saldo Kendaraan
            $oldSaldo = $kendaraan->saldo;
            $kendaraan->increment('saldo', $request->jumlah);

            // 5. Catat Riwayat Topup
            RiwayatTopup::create([
                'satker_id' => $kendaraan->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $request->jumlah,
                'tipe' => 'masuk',
                'metode' => 'manual',
                'status' => 'success',
                'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                'created_at' => \Carbon\Carbon::parse($request->tanggal_topup)->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::parse($request->tanggal_topup)->format('Y-m-d H:i:s'),
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

        $message = "<div class='text-left space-y-3'>";
        
        // Success Block
        $message .= "<div class='flex items-center gap-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100'>";
        $message .= "<div class='p-2 bg-emerald-500 text-white rounded-lg shadow-sm shrink-0'><svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg></div>";
        $message .= "<div><p class='text-[10px] font-bold text-emerald-600 uppercase tracking-widest'>Berhasil di Top Up</p><p class='text-lg font-black text-emerald-800 leading-tight'>{$import->successCount} <span class='text-xs font-semibold opacity-70'>Kendaraan</span></p></div>";
        $message .= "</div>";

        // Errors Block
        if (count($import->errors) > 0) {
            $message .= "<div class='p-3 bg-rose-50 rounded-xl border border-rose-100'>";
            $message .= "<p class='text-[10px] font-bold text-rose-600 uppercase tracking-widest mb-2 flex items-center gap-1.5'><svg class='w-3.5 h-3.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg> Terdeteksi " . count($import->errors) . " Kendala</p>";
            $message .= "<ul class='text-[11px] text-rose-800 space-y-1 font-medium'>";
            foreach (array_slice($import->errors, 0, 3) as $err) {
                $message .= "<li class='flex items-start gap-1.5'><span class='mt-1.5 w-1 h-1 rounded-full bg-rose-400 shrink-0'></span><span>{$err}</span></li>";
            }
            if (count($import->errors) > 3) {
                $message .= "<li class='pl-2.5 text-[10px] text-rose-500 italic font-bold mt-1'>... (+" . (count($import->errors) - 3) . " kendala lainnya)</li>";
            }
            $message .= "</ul></div>";
        }
        
        $message .= "</div>";

        return redirect()->route('admin.kendaraans.index')->with(
            $import->successCount > 0 ? 'success' : 'error',
            $message
        );
    }

    public function export(Request $request)
    {
        $satkerId = $request->input('satker_id');
        return Excel::download(new KendaraanExport($satkerId), 'data_kendaraan_' . date('Y-m-d_H-i') . '.xlsx');
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

    public function downloadFormat()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header di Row 2 (Row 1 kosong sesuai format import)
        $headers = ['NO', 'SATKER', 'KODE KENDARAAN', 'JENIS KENDARAAN', 'NOPOL', 'JENIS BBM', 'JUMLAH LITER'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($columns[$i] . '2', $header);
        }

        // Bold header
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        // Isi data kendaraan (tanpa saldo & pin)
        $kendaraans = Kendaraan::with('satker')->orderBy('satker_id')->get();
        $row = 3;
        $no = 1;
        foreach ($kendaraans as $k) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $k->satker->nama_satker ?? '-');
            $sheet->setCellValue('C' . $row, $k->kode_kendaraan ?? '-');
            $sheet->setCellValue('D' . $row, $k->jenis_kendaraan);
            $sheet->setCellValue('E' . $row, $k->no_polisi);
            $sheet->setCellValue('F' . $row, $k->jenis_bbm);
            // Kolom G (JUMLAH LITER) dikosongkan agar diisi user
            $row++;
            $no++;
        }

        // Auto-size columns
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'format_topup_saldo_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'format');
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kendaraans,id',
        ]);

        $count = Kendaraan::whereIn('id', $request->ids)->count();
        Kendaraan::whereIn('id', $request->ids)->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$count} Kendaraan secara massal"
        ]);

        return back()->with('success', "{$count} kendaraan berhasil dihapus.");
    }

    public function resetPin(Kendaraan $kendaraan)
    {
        if (auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'Hanya Super Admin yang dapat mereset PIN.');
        }

        $newPin = Kendaraan::generateUniquePin();
        $kendaraan->update(['pin' => $newPin]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Reset PIN Kendaraan (Super Admin): {$kendaraan->no_polisi}"
        ]);

        return back()->with('success', "PIN Kendaraan {$kendaraan->no_polisi} berhasil di-reset. PIN Baru: {$newPin}");
    }

    public function potongSaldo(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.1|max:' . $kendaraan->saldo,
            'kembalikan_ke_stok' => 'required|in:ya,tidak',
            'keterangan' => 'required|string|max:255',
            'topup_password' => 'required|string',
        ]);

        $user = auth()->user();

        // 0. Validasi Password Top Up
        if (!$user->topup_password) {
            return back()->with('error', 'Anda belum mengatur Password Top Up. Silakan atur di menu Profil > Password Top Up.');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->topup_password, $user->topup_password)) {
            return back()->with('error', 'Password Top Up salah! Transaksi dibatalkan.');
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $jumlah = $request->jumlah;
            $kembalikanKeStok = $request->kembalikan_ke_stok === 'ya';

            // 1. Kurangi Saldo Kendaraan
            $kendaraan->decrement('saldo', $jumlah);

            // 2. Catat Riwayat Pengurangan dari Kendaraan (RiwayatTopup tipe='keluar')
            RiwayatTopup::create([
                'satker_id' => $kendaraan->satker_id,
                'kendaraan_id' => $kendaraan->id,
                'user_id' => $user->id,
                'jumlah' => $jumlah,
                'tipe' => 'keluar',
                'metode' => 'potong_saldo',
                'status' => 'success',
                'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                'keterangan' => $request->keterangan,
            ]);

            // 3. Jika dikembalikan ke stok BBM, tambahkan ke AdminBbmStock
            if ($kembalikanKeStok) {
                $adminStock = \App\Models\AdminBbmStock::firstOrCreate(
                    ['jenis_bbm' => $kendaraan->jenis_bbm],
                    ['saldo' => 0]
                );
                
                $adminStock->increment('saldo', $jumlah);

                // Catat Riwayat Stok Admin
                \App\Models\RiwayatStokAdmin::create([
                    'user_id' => $user->id,
                    'jenis_bbm' => $kendaraan->jenis_bbm,
                    'jumlah' => $jumlah,
                    'tipe' => 'masuk',
                    'keterangan' => "Pengembalian saldo dari potong kendaraan {$kendaraan->no_polisi}. Ket: {$request->keterangan}",
                ]);
            }

            // 4. Log Aktivitas
            $statusHangus = $kembalikanKeStok ? 'dikembalikan ke stok pusat' : 'di-hangus-kan';
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Potong saldo kendaraan {$kendaraan->no_polisi} sebesar {$jumlah} L ({$statusHangus}). Ket: {$request->keterangan}"
            ]);

            DB::commit();
            return back()->with('success', "Saldo kendaraan berhasil dipotong sebesar {$jumlah} Liter dan {$statusHangus}.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Potong Saldo Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memotong saldo: ' . $e->getMessage());
        }
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'satker_id' => 'required|exists:satkers,id',
        ]);

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        $oldSatkerId = $kendaraan->satker_id;
        $newSatkerId = $request->satker_id;

        if ($oldSatkerId == $newSatkerId) {
            return back()->with('error', 'Kendaraan sudah berada di Satker tersebut.');
        }

        $newSatker = Satker::findOrFail($newSatkerId);
        $oldSatker = Satker::findOrFail($oldSatkerId);

        try {
            DB::beginTransaction();

            $saldoDipindah = $kendaraan->saldo;

            // 1. Catat Pengurangan Saldo di Satker Lama (jika saldo > 0)
            if ($saldoDipindah > 0) {
                RiwayatTopup::create([
                    'satker_id' => $oldSatkerId,
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => auth()->id(),
                    'jumlah' => $saldoDipindah,
                    'tipe' => 'keluar',
                    'metode' => 'TRANSFER',
                    'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                ]);

                // 2. Catat Penambahan Saldo di Satker Baru
                RiwayatTopup::create([
                    'satker_id' => $newSatkerId,
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => auth()->id(),
                    'jumlah' => $saldoDipindah,
                    'tipe' => 'masuk',
                    'metode' => 'TRANSFER',
                    'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                ]);
            }

            // 3. Update Satker dan Reset PIN
            $newPin = Kendaraan::generateUniquePin();
            $kendaraan->update([
                'satker_id' => $newSatkerId,
                'pin' => $newPin,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Memindahkan Kendaraan {$kendaraan->no_polisi} dari {$oldSatker->nama_satker} ke {$newSatker->nama_satker}. PIN baru digenerate."
            ]);

            DB::commit();

            return back()->with('success', "Kendaraan {$kendaraan->no_polisi} berhasil dipindahkan ke {$newSatker->nama_satker}. PIN baru: {$newPin}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Transfer Error: " . $e->getMessage());
            return back()->with('error', 'Gagal memindahkan kendaraan: ' . $e->getMessage());
        }
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
        $startDateWita = \Carbon\Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Makassar');
        $endDateWita = $startDateWita->copy()->endOfMonth();
        $daysInMonth = $startDateWita->daysInMonth;

        // Convert WITA boundaries to UTC for DB querying
        $startUtc = $startDateWita->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $endUtc = $endDateWita->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');

        // Ambil kendaraan yang saat ini di Satker ini ATAU pernah punya aktifitas di Satker ini pada bulan tsb
        $kendaraansInSatker = \App\Models\Kendaraan::where('satker_id', $satkerId)->pluck('id')->toArray();
        $kendaraansWithActivity = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
            ->whereBetween('tanggal', [$startUtc, $endUtc])
            ->distinct()
            ->pluck('kendaraan_id')
            ->toArray();
        $kendaraansWithTopup = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
            ->whereBetween('created_at', [$startUtc, $endUtc])
            ->distinct()
            ->pluck('kendaraan_id')
            ->toArray();
        
        $allRelevantIds = array_unique(array_merge($kendaraansInSatker, $kendaraansWithActivity, $kendaraansWithTopup));
        $kendaraans = \App\Models\Kendaraan::whereIn('id', $allRelevantIds)->orderBy('jenis_bbm')->orderBy('no_polisi')->get();

        // Bulan sebelumnya dalam WITA lalu konversi ke UTC
        $prevMonthEndWita = $startDateWita->copy()->subDay()->endOfDay();
        $prevMonthEndUtc = $prevMonthEndWita->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
        
        $namaBulan = $startDateWita->translatedFormat('F');
        $namaBulanSebelumnya = $prevMonthEndWita->translatedFormat('F');

        $rows = [];
        $summaryByBbm = [];

        foreach ($kendaraans as $kendaraan) {
            // Top Up bulan ini (Masuk - Keluar di Satker ini)
            $topupMasuk = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'masuk')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->sum('jumlah');
            
            $topupKeluar = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'keluar')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->sum('jumlah');

            $topupBulanIni = $topupMasuk; // Hanya saldo MASUK yang dihitung sebagai Top Up di laporan

            // Total top up sampai akhir bulan lalu di Satker ini
            $totalTopupSampaiSebelumnyaMasuk = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'masuk')
                ->where('created_at', '<=', $prevMonthEndUtc)
                ->sum('jumlah');
            
            $totalTopupSampaiSebelumnyaKeluar = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'keluar')
                ->where('created_at', '<=', $prevMonthEndUtc)
                ->sum('jumlah');
            
            $totalTopupSampaiSebelumnya = $totalTopupSampaiSebelumnyaMasuk - $totalTopupSampaiSebelumnyaKeluar;

            // Total pemakaian (transaksi) sampai akhir bulan lalu di Satker ini
            $totalPemakaianSampaiSebelumnya = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tanggal', '<=', $prevMonthEndUtc)
                ->sum('liter');

            // Total hutang (bon) sampai akhir bulan lalu untuk kendaraan ini
            $totalHutangSampaiSebelumnya = \App\Models\Hutang::where('satker_id', $satkerId)
                ->where('nopol', $kendaraan->no_polisi)
                ->where('jenis_bbm', $kendaraan->jenis_bbm)
                ->where('tanggal_bon', '<', $startDateWita->format('Y-m-d'))
                ->sum('jumlah_bon');

            // Total transfer keluar (saldo personil) sampai akhir bulan lalu di Satker ini
            $totalTransferKeluarSebelumnya = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEndUtc)
                ->sum('jumlah');

            // Total transfer masuk (TM) sampai akhir bulan lalu di Satker ini
            $totalTmSampaiSebelumnya1 = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('tujuan_kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEndUtc)
                ->sum('jumlah');
            
            $totalTmSampaiSebelumnya2 = \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satkerId)
                ->where('target_kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEndUtc)
                ->sum('jumlah');
            
            $totalTmSampaiSebelumnya = $totalTmSampaiSebelumnya1 + $totalTmSampaiSebelumnya2;

            // Sisa BBM bulan lalu = (total top up masuk + total TM) - (total top up keluar + total pemakaian + total hutang + total transfer keluar)
            $sisaBulanLalu = ($totalTopupSampaiSebelumnyaMasuk + $totalTmSampaiSebelumnya) - ($totalTopupSampaiSebelumnyaKeluar + $totalPemakaianSampaiSebelumnya + $totalHutangSampaiSebelumnya + $totalTransferKeluarSebelumnya);
            if ($sisaBulanLalu < 0) $sisaBulanLalu = 0;

            // Transfer Masuk (TM) bulan ini
            $tmBulanIni1 = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('tujuan_kendaraan_id', $kendaraan->id)
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->sum('jumlah');
            
            $tmBulanIni2 = \App\Models\RiwayatTransferAntarPersonel::where('satker_id', $satkerId)
                ->where('target_kendaraan_id', $kendaraan->id)
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->sum('jumlah');

            $tmBulanIni = $tmBulanIni1 + $tmBulanIni2;

            // Transfer Keluar (TK) bulan ini (ke personil ATAU kendaraan lain ATAU potong saldo central)
            $tkBulanIni = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->sum('jumlah');
            
            // Gabungkan transfer keluar dengan potong saldo (keluar)
            $tkBulanIni = $tkBulanIni + $topupKeluar;

            $totalBbm = $sisaBulanLalu + $topupBulanIni + $tmBulanIni;
            
            // Pemakaian per hari bulan ini di Satker ini
            $dailyUsage = [];
            $totalPemakaian = 0;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateStartWita = \Carbon\Carbon::create($tahun, $bulan, $d, 0, 0, 0, 'Asia/Makassar');
                $dateEndWita = $dateStartWita->copy()->endOfDay();
                $dayStartUtc = $dateStartWita->setTimezone('UTC')->format('Y-m-d H:i:s');
                $dayEndUtc = $dateEndWita->setTimezone('UTC')->format('Y-m-d H:i:s');

                $usage = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
                    ->where('kendaraan_id', $kendaraan->id)
                    ->whereBetween('tanggal', [$dayStartUtc, $dayEndUtc])
                    ->sum('liter');

                // Tambahkan hutang (bon) pada tanggal ini
                $usageHutang = \App\Models\Hutang::where('satker_id', $satkerId)
                    ->where('nopol', $kendaraan->no_polisi)
                    ->where('jenis_bbm', $kendaraan->jenis_bbm)
                    ->where('tanggal_bon', $dateStartWita->format('Y-m-d'))
                    ->sum('jumlah_bon');
                $usage += $usageHutang;

                $dailyUsage[$d] = $usage > 0 ? round($usage, 0) : null;
                $totalPemakaian += $usage;
            }

            // Total Pemakaian = Total Harian + Transfer Keluar (Transfer dianggap pemakaian)
            $totalPemakaian += $tkBulanIni;

            // Sisa BBM = Total BBM - Total Pemakaian
            $sisaBbm = $totalBbm - $totalPemakaian;

            $row = [
                'kode_kendaraan' => $kendaraan->kode_kendaraan ?? '-',
                'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
                'no_polisi' => $kendaraan->no_polisi,
                'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
                'sisa_bulan_lalu' => round($sisaBulanLalu, 0),
                'topup_bulan_ini' => round($topupBulanIni, 0),
                'tm_bulan_ini' => round($tmBulanIni, 0),
                'total_bbm' => round($totalBbm, 0),
                'tk_bulan_ini' => round($tkBulanIni, 0), 
                'has_transfer' => $tkBulanIni > 0,
                'daily_usage' => $dailyUsage,
                'total_pemakaian' => round($totalPemakaian, 0),
                'sisa_bbm' => round($sisaBbm, 0),
            ];
            $rows[] = $row;

            // Summary per jenis BBM
            $bbm = $row['jenis_bbm'];
            if (!isset($summaryByBbm[$bbm])) {
                $summaryByBbm[$bbm] = [
                    'sisa_bulan_lalu' => 0,
                    'topup_bulan_ini' => 0,
                    'tm_bulan_ini' => 0,
                    'total_bbm' => 0,
                    'tk_bulan_ini' => 0,
                    'total_pemakaian' => 0,
                    'sisa_bbm' => 0,
                ];
            }
            $summaryByBbm[$bbm]['sisa_bulan_lalu'] += $row['sisa_bulan_lalu'];
            $summaryByBbm[$bbm]['topup_bulan_ini'] += $row['topup_bulan_ini'];
            $summaryByBbm[$bbm]['tm_bulan_ini'] += $row['tm_bulan_ini'];
            $summaryByBbm[$bbm]['total_bbm'] += $row['total_bbm'];
            $summaryByBbm[$bbm]['tk_bulan_ini'] += $row['tk_bulan_ini'];
            $summaryByBbm[$bbm]['total_pemakaian'] += $row['total_pemakaian'];
            $summaryByBbm[$bbm]['sisa_bbm'] += $row['sisa_bbm'];
        }

        return compact('rows', 'summaryByBbm', 'daysInMonth', 'bulan', 'tahun', 'namaBulan', 'namaBulanSebelumnya', 'satkerId');
    }

    /**
     * Preview Import Kendaraan - Returns JSON preview of what will be imported
     */
    public function previewImportKendaraan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $filePath = $request->file('file')->getRealPath();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            // Auto-detect header row by looking for "NOPOL" keyword in first 5 rows
            $headerRow = null;
            $colMap = [];
            for ($r = 1; $r <= min(5, $highestRow); $r++) {
                foreach (range('A', $highestCol) as $col) {
                    $val = strtolower(trim((string) $sheet->getCell($col . $r)->getValue()));
                    if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                        $headerRow = $r;
                        break 2;
                    }
                }
            }

            if (!$headerRow) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header tidak ditemukan. Pastikan file memiliki kolom "NOPOL" di salah satu baris pertama.',
                ], 422);
            }

            // Build column map from header row
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                    $colMap['nopol'] = $col;
                } elseif (in_array($val, ['jenis kendaraan', 'jenis_kendaraan', 'jenis', 'tipe'])) {
                    $colMap['jenis_kendaraan'] = $col;
                } elseif (in_array($val, ['jenis bbm', 'jenis_bbm', 'bbm', 'bahan bakar'])) {
                    $colMap['jenis_bbm'] = $col;
                } elseif (in_array($val, ['satker', 'satuan kerja', 'satuan_kerja', 'nama_satker'])) {
                    $colMap['satker'] = $col;
                }
            }

            Log::info('Import Kendaraan Preview: headerRow=' . $headerRow . ', colMap=' . json_encode($colMap) . ', totalRows=' . $highestRow);

            $newEntries = [];
            $duplicates = [];
            $errors = [];
            $successCount = 0;

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nopol = isset($colMap['nopol']) ? trim((string) $sheet->getCell($colMap['nopol'] . $r)->getValue()) : '';
                $jenisKendaraan = isset($colMap['jenis_kendaraan']) ? trim((string) $sheet->getCell($colMap['jenis_kendaraan'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : '';
                $satkerName = isset($colMap['satker']) ? trim((string) $sheet->getCell($colMap['satker'] . $r)->getValue()) : '';

                // Skip empty rows
                if (empty($nopol) && empty($jenisKendaraan) && empty($jenisBbm)) {
                    continue;
                }

                if (empty($nopol)) { $errors[] = "Baris {$r}: NOPOL kosong."; continue; }
                if (empty($jenisKendaraan)) { $errors[] = "Baris {$r}: JENIS KENDARAAN kosong."; continue; }
                if (empty($jenisBbm)) { $errors[] = "Baris {$r}: JENIS BBM kosong."; continue; }

                // Normalize BBM
                $bbmLower = strtolower($jenisBbm);
                if ($bbmLower === 'pertamax') { $jenisBbm = 'Pertamax'; }
                elseif (in_array($bbmLower, ['pertamina dex', 'pertaminadex', 'dex'])) { $jenisBbm = 'Pertamina Dex'; }
                else { $errors[] = "Baris {$r}: Jenis BBM '{$jenisBbm}' tidak valid (Pertamax/Pertamina Dex)."; continue; }

                // Resolve satker
                $resolvedSatkerId = null;
                $resolvedSatkerName = '-';
                if (!empty($satkerName)) {
                    $satker = Satker::where('nama_satker', 'LIKE', '%' . $satkerName . '%')->first();
                    if (!$satker) { $errors[] = "Baris {$r}: Satker '{$satkerName}' tidak ditemukan."; continue; }
                    $resolvedSatkerId = $satker->id;
                    $resolvedSatkerName = $satker->nama_satker;
                } else {
                    $errors[] = "Baris {$r}: SATKER kosong.";
                    continue;
                }

                // Check duplicate
                $existing = Kendaraan::where('no_polisi', $nopol)->first();
                if ($existing) {
                    $changes = [];
                    if ($existing->jenis_kendaraan !== $jenisKendaraan) {
                        $changes[] = ['field' => 'Jenis Kendaraan', 'old' => $existing->jenis_kendaraan, 'new' => $jenisKendaraan];
                    }
                    if ($existing->jenis_bbm !== $jenisBbm) {
                        $changes[] = ['field' => 'Jenis BBM', 'old' => $existing->jenis_bbm, 'new' => $jenisBbm];
                    }
                    if ($existing->satker_id != $resolvedSatkerId) {
                        $oldSatker = $existing->satker ? $existing->satker->nama_satker : '-';
                        $changes[] = ['field' => 'Satker', 'old' => $oldSatker, 'new' => $resolvedSatkerName];
                    }
                    $duplicates[] = [
                        'row' => $r, 'no_polisi' => $nopol, 'changes' => $changes, 'has_changes' => count($changes) > 0,
                    ];
                } else {
                    $newEntries[] = [
                        'row' => $r, 'no_polisi' => $nopol, 'jenis_kendaraan' => $jenisKendaraan,
                        'jenis_bbm' => $jenisBbm, 'satker' => $resolvedSatkerName,
                    ];
                    $successCount++;
                }
            }

            return response()->json([
                'success' => true,
                'new_count' => $successCount,
                'duplicate_count' => count($duplicates),
                'error_count' => count($errors),
                'new_entries' => $newEntries,
                'duplicates' => $duplicates,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            Log::error('Import Kendaraan Preview Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Import Kendaraan - Process the actual import
     */
    public function importKendaraan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'duplicate_action' => 'required|in:skip,update',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'duplicate_action.required' => 'Pilih aksi untuk data duplikat.',
        ]);

        try {
            $filePath = $request->file('file')->getRealPath();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();
            $duplicateAction = $request->duplicate_action;

            // Auto-detect header row
            $headerRow = null;
            for ($r = 1; $r <= min(5, $highestRow); $r++) {
                foreach (range('A', $highestCol) as $col) {
                    $val = strtolower(trim((string) $sheet->getCell($col . $r)->getValue()));
                    if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                        $headerRow = $r;
                        break 2;
                    }
                }
            }

            if (!$headerRow) {
                return redirect()->route('admin.kendaraans.index')->with('error', 'Header NOPOL tidak ditemukan dalam file.');
            }

            // Build column map
            $colMap = [];
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                    $colMap['nopol'] = $col;
                } elseif (in_array($val, ['jenis kendaraan', 'jenis_kendaraan', 'jenis', 'tipe'])) {
                    $colMap['jenis_kendaraan'] = $col;
                } elseif (in_array($val, ['jenis bbm', 'jenis_bbm', 'bbm', 'bahan bakar'])) {
                    $colMap['jenis_bbm'] = $col;
                } elseif (in_array($val, ['satker', 'satuan kerja', 'satuan_kerja', 'nama_satker'])) {
                    $colMap['satker'] = $col;
                }
            }

            $successCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nopol = isset($colMap['nopol']) ? trim((string) $sheet->getCell($colMap['nopol'] . $r)->getValue()) : '';
                $jenisKendaraan = isset($colMap['jenis_kendaraan']) ? trim((string) $sheet->getCell($colMap['jenis_kendaraan'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : '';
                $satkerName = isset($colMap['satker']) ? trim((string) $sheet->getCell($colMap['satker'] . $r)->getValue()) : '';

                if (empty($nopol) && empty($jenisKendaraan) && empty($jenisBbm)) continue;
                if (empty($nopol)) { $errors[] = "Baris {$r}: NOPOL kosong."; continue; }
                if (empty($jenisKendaraan)) { $errors[] = "Baris {$r}: JENIS KENDARAAN kosong."; continue; }
                if (empty($jenisBbm)) { $errors[] = "Baris {$r}: JENIS BBM kosong."; continue; }

                // Normalize BBM
                $bbmLower = strtolower($jenisBbm);
                if ($bbmLower === 'pertamax') { $jenisBbm = 'Pertamax'; }
                elseif (in_array($bbmLower, ['pertamina dex', 'pertaminadex', 'dex'])) { $jenisBbm = 'Pertamina Dex'; }
                else { $errors[] = "Baris {$r}: BBM '{$jenisBbm}' tidak valid."; continue; }

                // Resolve satker
                if (empty($satkerName)) { $errors[] = "Baris {$r}: SATKER kosong."; continue; }
                $satker = Satker::where('nama_satker', 'LIKE', '%' . $satkerName . '%')->first();
                if (!$satker) { $errors[] = "Baris {$r}: Satker '{$satkerName}' tidak ditemukan."; continue; }

                // Check duplicate
                $existing = Kendaraan::where('no_polisi', $nopol)->first();
                if ($existing) {
                    if ($duplicateAction === 'update') {
                        $existing->update([
                            'jenis_kendaraan' => $jenisKendaraan,
                            'jenis_bbm' => $jenisBbm,
                            'satker_id' => $satker->id,
                        ]);
                        $updatedCount++;
                    } else {
                        $skippedCount++;
                    }
                    continue;
                }

                // Create new
                $lastId = Kendaraan::max('id') ?? 0;
                $kodeKendaraan = 'KND-' . str_pad($lastId + 1 + $successCount, 5, '0', STR_PAD_LEFT);
                $barcode = strtoupper(\Illuminate\Support\Str::random(10));
                while (Kendaraan::where('barcode', $barcode)->exists()) {
                    $barcode = strtoupper(\Illuminate\Support\Str::random(10));
                }
                $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                Kendaraan::create([
                    'satker_id' => $satker->id,
                    'kode_kendaraan' => $kodeKendaraan,
                    'no_polisi' => $nopol,
                    'jenis_kendaraan' => $jenisKendaraan,
                    'jenis_bbm' => $jenisBbm,
                    'barcode' => $barcode,
                    'pin' => $pin,
                    'saldo' => 0,
                ]);
                $successCount++;
            }

            // Log activity
            if ($successCount > 0 || $updatedCount > 0) {
                $msg = "Import Excel kendaraan:";
                if ($successCount > 0) $msg .= " {$successCount} baru";
                if ($updatedCount > 0) $msg .= " {$updatedCount} diperbarui";
                if ($skippedCount > 0) $msg .= " {$skippedCount} dilewati";
                \App\Models\LogAktivitas::create(['user_id' => auth()->id(), 'aktivitas' => $msg]);
            }

            $messages = [];
            if ($successCount > 0) $messages[] = "{$successCount} kendaraan baru berhasil ditambahkan";
            if ($updatedCount > 0) $messages[] = "{$updatedCount} kendaraan diperbarui";
            if ($skippedCount > 0) $messages[] = "{$skippedCount} kendaraan duplikat dilewati";

            $message = 'Import selesai! ' . implode(', ', $messages) . '.';
            if (count($errors) > 0) {
                $errorList = implode(' | ', array_slice($errors, 0, 5));
                $message .= " Terdapat " . count($errors) . " error: {$errorList}";
            }

            $status = ($successCount > 0 || $updatedCount > 0) ? 'success' : 'error';
            if ($status === 'error' && count($errors) === 0 && $skippedCount > 0) $status = 'info';

            return redirect()->route('admin.kendaraans.index')->with($status, $message);
        } catch (\Exception $e) {
            Log::error('Import Kendaraan Error: ' . $e->getMessage());
            return redirect()->route('admin.kendaraans.index')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Download Import Kendaraan Template
     */
    public function downloadImportKendaraanTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Kendaraan');

        // Header di Row 2 (Row 1 kosong sesuai format)
        $headers = ['NO', 'SATKER', 'JENIS KENDARAAN', 'NOPOL', 'JENIS BBM'];
        $columns = ['A', 'B', 'C', 'D', 'E'];
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($columns[$i] . '2', $header);
        }

        // Bold header + styling
        $headerStyle = $sheet->getStyle('A2:E2');
        $headerStyle->getFont()->setBold(true)->setSize(11);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Contoh data di Row 3
        $sheet->setCellValue('A3', 1);
        $sheet->setCellValue('B3', 'BIRO LOGISTIK');
        $sheet->setCellValue('C3', 'Mobil Dinas');
        $sheet->setCellValue('D3', 'AB 1234 CD');
        $sheet->setCellValue('E3', 'Pertamax');

        // Contoh data di Row 4
        $sheet->setCellValue('A4', 2);
        $sheet->setCellValue('B4', 'BIRO LOGISTIK');
        $sheet->setCellValue('C4', 'Motor Dinas');
        $sheet->setCellValue('D4', 'AB 5678 EF');
        $sheet->setCellValue('E4', 'Pertamina Dex');

        // Example row style (italic, gray)
        $exampleStyle = $sheet->getStyle('A3:E4');
        $exampleStyle->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF888888'));

        // Auto-size columns
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add note in Row 1
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA KENDARAAN — Hapus baris contoh (baris 3-4) sebelum import');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFF0000'));
        $sheet->mergeCells('A1:E1');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'template_import_kendaraan.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'import_knd');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}

