<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBulananExport;
use App\Exports\KendaraanTemplateExport;
use App\Imports\KendaraanImport;
use App\Models\LogAktivitas;
use Barryvdh\DomPDF\Facade\Pdf;

class KendaraanController extends Controller
{
    public function index()
    {
        $satkerId = auth()->user()->satker_id;
        $kendaraans = Kendaraan::where('satker_id', $satkerId)->latest()->paginate(10);
        $personels = \App\Models\Personel::where('satker_id', $satkerId)->get();
        $availableKendaraans = Kendaraan::where('satker_id', $satkerId)->get();
        
        return view('satker.kendaraans.index', compact('kendaraans', 'personels', 'availableKendaraans'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'personel_id' => 'required|exists:personels,id',
            'jumlah' => 'required|numeric|min:0.1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $satkerId = auth()->user()->satker_id;
        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)->where('satker_id', $satkerId)->firstOrFail();
        $personel = \App\Models\Personel::where('id', $request->personel_id)->where('satker_id', $satkerId)->firstOrFail();

        if ($kendaraan->saldo < $request->jumlah) {
            return back()->with('error', 'Saldo kendaraan tidak mencukupi.');
        }

        // Tolak transfer jika personel sudah punya jenis BBM berbeda
        if ($personel->jenis_bbm && $personel->jenis_bbm !== $kendaraan->jenis_bbm) {
            return back()->with('error', 'Transfer ditolak! Personel "' . $personel->nama . '" sudah memiliki jenis BBM ' . $personel->jenis_bbm . '. Tidak bisa menerima BBM ' . $kendaraan->jenis_bbm . '.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($kendaraan, $personel, $request, $satkerId) {
            // Kurangi saldo kendaraan
            $kendaraan->decrement('saldo', $request->jumlah);

            // Tambah saldo personel & update jenis BBM dari kendaraan sumber
            $personel->increment('saldo', $request->jumlah);
            $personel->update(['jenis_bbm' => $kendaraan->jenis_bbm]);

            // Catat riwayat
            \App\Models\RiwayatTransferSaldoPersonel::create([
                'satker_id' => $satkerId,
                'kendaraan_id' => $kendaraan->id,
                'personel_id' => $personel->id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Transfer saldo BBM: {$request->jumlah} L dari Kendaraan ({$kendaraan->no_polisi}) ke Personel ({$personel->nama})"
            ]);
        });

        return back()->with('success', 'Transfer saldo ke personel berhasil.');
    }

    public function laporanTransfer(Request $request)
    {
        $satkerId = auth()->user()->satker_id;

        $query = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
            ->with(['kendaraan', 'personel']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $riwayats = $query->latest()->paginate(15)->appends($request->query());

        // Summary total per jenis BBM (from all filtered, not just current page)
        $summaryQuery = \App\Models\RiwayatTransferSaldoPersonel::where('riwayat_transfer_saldo_personels.satker_id', $satkerId)
            ->join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id');

        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('riwayat_transfer_saldo_personels.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('riwayat_transfer_saldo_personels.created_at', '<=', $request->end_date);
        }

        $summary = $summaryQuery->selectRaw('kendaraans.jenis_bbm, SUM(riwayat_transfer_saldo_personels.jumlah) as total')
            ->groupBy('kendaraans.jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('satker.kendaraans.laporan-transfer', compact('riwayats', 'summary'));
    }

    public function printLaporanTransfer(Request $request)
    {
        $satkerId = auth()->user()->satker_id;

        $query = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
            ->with(['kendaraan', 'personel']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $riwayats = $query->latest()->get();

        // Summary per jenis BBM
        $summaryQuery = \App\Models\RiwayatTransferSaldoPersonel::where('riwayat_transfer_saldo_personels.satker_id', $satkerId)
            ->join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id');

        if ($request->filled('start_date')) {
            $summaryQuery->whereDate('riwayat_transfer_saldo_personels.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $summaryQuery->whereDate('riwayat_transfer_saldo_personels.created_at', '<=', $request->end_date);
        }

        $summary = $summaryQuery->selectRaw('kendaraans.jenis_bbm, SUM(riwayat_transfer_saldo_personels.jumlah) as total')
            ->groupBy('kendaraans.jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $satkerName = auth()->user()->satker->nama_satker ?? '';

        $pdf = Pdf::loadView('satker.kendaraans.laporan-transfer-print', compact('riwayats', 'summary', 'satkerName'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-transfer-saldo-' . date('Y-m-d_H-i') . '.pdf');
    }

    public function laporanBulanan(Request $request)
    {
        $satkerId = auth()->user()->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $data['satker'] = auth()->user()->satker;

        return view('satker.kendaraans.laporan-bulanan', $data);
    }

    public function printLaporanBulanan(Request $request)
    {
        $satkerId = auth()->user()->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $data['satkerName'] = auth()->user()->satker->nama_satker ?? '';

        $pdf = Pdf::loadView('satker.kendaraans.laporan-bulanan-print', $data)
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-bulanan-bbm-' . $data['namaBulan'] . '-' . $tahun . '.pdf');
    }

    public function exportLaporanBulanan(Request $request)
    {
        $satkerId = auth()->user()->satker_id;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $data = $this->buildLaporanBulananData($satkerId, $bulan, $tahun);
        $data['satker'] = auth()->user()->satker;
        $data['satkerName'] = auth()->user()->satker->nama_satker ?? '';

        $fileName = 'laporan-bulanan-bbm-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $data['satkerName']) . '-' . $data['namaBulan'] . '-' . $tahun . '.xlsx';

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
        $prevBulan = $prevMonthEnd->month;
        $prevTahun = $prevMonthEnd->year;

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

            // Total BBM = Sisa bulan lalu + Top Up bulan ini (Transfer TIDAK mengurangi Total BBM di sini)
            $totalBbm = $sisaBulanLalu + $topupBulanIni;
            // if ($totalBbm < 0) $totalBbm = 0; // Logic removed/adjusted

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
                'transfer_bulan_ini' => round($transferBulanIni, 0), // NEW data
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

        return compact('rows', 'summaryByBbm', 'daysInMonth', 'bulan', 'tahun', 'namaBulan', 'namaBulanSebelumnya');
    }

    public function print(Kendaraan $kendaraan)
    {
        if ($kendaraan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        return view('satker.kendaraans.print', compact('kendaraan'));
    }

    public function create()
    {
        $canCreate = \App\Models\Setting::where('key', 'satker_can_create_kendaraan')->value('value') ?? 1;
        if (!$canCreate) {
             return redirect()->route('satker.kendaraans.index')->with('error', 'Fitur tambah kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        return view('satker.kendaraans.create');
    }

    public function store(Request $request)
    {
        $canCreate = \App\Models\Setting::where('key', 'satker_can_create_kendaraan')->value('value') ?? 1;
        if (!$canCreate) {
             return redirect()->route('satker.kendaraans.index')->with('error', 'Fitur tambah kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        $request->validate([
            'no_polisi' => 'required|string|max:20|unique:kendaraans',
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
        ]);

        // Auto-generate unique barcode
        $barcode = strtoupper(Str::random(10));
        while (Kendaraan::where('barcode', $barcode)->exists()) {
            $barcode = strtoupper(Str::random(10));
        }

        // Auto-generate 6-digit PIN
        $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Generate kode kendaraan otomatis: KND-00001
        $lastId = Kendaraan::max('id') ?? 0;
        $kodeKendaraan = 'KND-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        Kendaraan::create([
            'satker_id' => auth()->user()->satker_id,
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
            'aktivitas' => "Menambahkan Kendaraan Baru: {$request->no_polisi} ({$request->jenis_kendaraan})"
        ]);

        return redirect()->route('satker.kendaraans.index')->with('success', 'Kendaraan berhasil ditambahkan! Kode: ' . $kodeKendaraan . ' | Barcode: ' . $barcode . ' | PIN: ' . $pin . ' (Simpan PIN ini, tidak bisa dilihat lagi!)');
    }
    public function edit(Kendaraan $kendaraan)
    {
        if ($kendaraan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }
        
        $canEdit = \App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1;
        if (!$canEdit) {
             return redirect()->route('satker.kendaraans.index')->with('error', 'Fitur edit kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        return view('satker.kendaraans.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        if ($kendaraan->satker_id !== auth()->user()->satker_id) {
            abort(403);
        }

        $canEdit = \App\Models\Setting::where('key', 'satker_can_edit_kendaraan')->value('value') ?? 1;
        if (!$canEdit) {
             return redirect()->route('satker.kendaraans.index')->with('error', 'Fitur edit kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        $request->validate([
            'no_polisi' => ['required', 'string', 'max:20', Rule::unique('kendaraans')->ignore($kendaraan->id)],
            'jenis_kendaraan' => 'required|string',
            'jenis_bbm' => 'required|string',
        ]);

        $kendaraan->update([
            'no_polisi' => $request->no_polisi,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'jenis_bbm' => $request->jenis_bbm,
        ]);

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui data Kendaraan: {$kendaraan->no_polisi}"
        ]);

        return redirect()->route('satker.kendaraans.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function previewImport(Request $request)
    {
        $canImport = \App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1';
        if ($canImport == '0') {
            return response()->json(['error' => 'Fitur import kendaraan saat ini dinonaktifkan oleh Administrator.'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $satkerId = auth()->user()->satker_id;
        $import = new KendaraanImport($satkerId, 'preview');

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membaca file: ' . $e->getMessage()], 422);
        }

        return response()->json([
            'duplicates' => $import->duplicates,
            'errors' => $import->errors,
        ]);
    }

    public function importKendaraan(Request $request)
    {
        $canImport = \App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1';
        if ($canImport == '0') {
            return back()->with('error', 'Fitur import kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'duplicate_action' => 'nullable|in:skip,update',
        ], [
            'file.required' => 'File Excel harus dipilih.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $satkerId = auth()->user()->satker_id;
        $duplicateAction = $request->input('duplicate_action', 'skip');
        $import = new KendaraanImport($satkerId, $duplicateAction);

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $messages = [];
        if ($import->successCount > 0) {
            $messages[] = "{$import->successCount} kendaraan baru ditambahkan";
        }
        if ($import->updatedCount > 0) {
            $messages[] = "{$import->updatedCount} data diperbarui";
        }
        if ($import->skippedCount > 0) {
            $messages[] = "{$import->skippedCount} data duplikat dilewati";
        }

        if (count($import->errors) > 0) {
            $errorMessages = implode(' | ', array_slice($import->errors, 0, 5));
            if ($import->successCount > 0 || $import->updatedCount > 0) {
                $message = implode(', ', $messages) . ". Ada " . count($import->errors) . " baris gagal: {$errorMessages}";
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Import gagal. ' . $errorMessages);
            }
        }

        $message = implode(', ', $messages) . '.';
        return back()->with('success', $message ?: 'Tidak ada data baru untuk diimport.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new KendaraanTemplateExport(), 'template-import-kendaraan.xlsx');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kendaraans,id',
        ]);

        $satkerId = auth()->user()->satker_id;
        $deleted = Kendaraan::where('satker_id', $satkerId)
            ->whereIn('id', $request->ids)
            ->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus {$deleted} kendaraan secara massal",
        ]);

        return redirect()->route('satker.kendaraans.index')->with('success', "{$deleted} kendaraan berhasil dihapus.");
    }
}

