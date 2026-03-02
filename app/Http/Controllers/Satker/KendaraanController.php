<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBulananExport;
use App\Exports\KendaraanTemplateExport;
use App\Imports\KendaraanImport;
use App\Models\LogAktivitas;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class KendaraanController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $satkerId = auth()->user()->satker_id;
        $search = $request->input('search');

        $query = Kendaraan::where('satker_id', $satkerId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                    ->orWhere('kode_kendaraan', 'like', "%{$search}%")
                    ->orWhere('jenis_kendaraan', 'like', "%{$search}%");
            });
        }

        $kendaraans = $query->latest()->paginate($this->getPerPage($request))->withQueryString();
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
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endUtc);
        }

        $perPage = $this->getPerPage($request);
        $riwayats = $query->latest()->paginate($perPage)->withQueryString();

        // Summary total per jenis BBM (from all filtered, not just current page)
        $summaryQuery = \App\Models\RiwayatTransferSaldoPersonel::where('riwayat_transfer_saldo_personels.satker_id', $satkerId)
            ->join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id');

        if ($request->filled('start_date')) {
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $summaryQuery->where('riwayat_transfer_saldo_personels.created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $summaryQuery->where('riwayat_transfer_saldo_personels.created_at', '<=', $endUtc);
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
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endUtc);
        }

        $riwayats = $query->latest()->get();

        // Summary per jenis BBM
        $summaryQuery = \App\Models\RiwayatTransferSaldoPersonel::where('riwayat_transfer_saldo_personels.satker_id', $satkerId)
            ->join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id');

        if ($request->filled('start_date')) {
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $summaryQuery->where('riwayat_transfer_saldo_personels.created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $summaryQuery->where('riwayat_transfer_saldo_personels.created_at', '<=', $endUtc);
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
        // Ambil kendaraan yang saat ini di Satker ini ATAU pernah punya aktifitas di Satker ini pada bulan tsb
        $kendaraansInSatker = \App\Models\Kendaraan::where('satker_id', $satkerId)->pluck('id')->toArray();
        $kendaraansWithActivity = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->distinct()
            ->pluck('kendaraan_id')
            ->toArray();
        $kendaraansWithTopup = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->distinct()
            ->pluck('kendaraan_id')
            ->toArray();
        
        $allRelevantIds = array_unique(array_merge($kendaraansInSatker, $kendaraansWithActivity, $kendaraansWithTopup));
        $kendaraans = \App\Models\Kendaraan::whereIn('id', $allRelevantIds)->orderBy('jenis_bbm')->orderBy('no_polisi')->get();

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
            // Top Up bulan ini (Masuk - Keluar di Satker ini)
            $topupMasuk = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'masuk')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('jumlah');
            
            $topupKeluar = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'keluar')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('jumlah');

            $topupBulanIni = $topupMasuk - $topupKeluar;

            // Total top up sampai akhir bulan lalu di Satker ini
            $totalTopupSampaiSebelumnyaMasuk = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'masuk')
                ->where('created_at', '<=', $prevMonthEnd)
                ->sum('jumlah');
            
            $totalTopupSampaiSebelumnyaKeluar = \App\Models\RiwayatTopup::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tipe', 'keluar')
                ->where('created_at', '<=', $prevMonthEnd)
                ->sum('jumlah');
            
            $totalTopupSampaiSebelumnya = $totalTopupSampaiSebelumnyaMasuk - $totalTopupSampaiSebelumnyaKeluar;

            // Total pemakaian (transaksi) sampai akhir bulan lalu di Satker ini
            $totalPemakaianSampaiSebelumnya = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('tanggal', '<=', $prevMonthEnd)
                ->sum('liter');

            // Total transfer keluar (saldo personil) sampai akhir bulan lalu di Satker ini
            $totalTransferKeluarSebelumnya = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->where('created_at', '<=', $prevMonthEnd)
                ->sum('jumlah');

            // Sisa BBM bulan lalu = total top up - total pemakaian - total transfer keluar
            $sisaBulanLalu = $totalTopupSampaiSebelumnya - $totalPemakaianSampaiSebelumnya - $totalTransferKeluarSebelumnya;
            if ($sisaBulanLalu < 0) $sisaBulanLalu = 0;

            // Transfer keluar (ke personil) bulan ini di Satker ini
            $transferBulanIni = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->where('kendaraan_id', $kendaraan->id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('jumlah');

            $totalBbm = $sisaBulanLalu + $topupBulanIni;
            
            // Pemakaian per hari bulan ini di Satker ini
            $dailyUsage = [];
            $totalPemakaian = 0;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = \Carbon\Carbon::create($tahun, $bulan, $d);
                $usage = \App\Models\TransaksiBbm::where('satker_id', $satkerId)
                    ->where('kendaraan_id', $kendaraan->id)
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
                'jenis_bbm' => $kendaraan->jenis_bbm ?: 'TANPA JENIS',
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
            $bbm = $kendaraan->jenis_bbm ?: 'TANPA JENIS';
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
            return response()->json(['success' => false, 'message' => 'Fitur import kendaraan saat ini dinonaktifkan oleh Administrator.'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $satkerId = auth()->user()->satker_id;
        $satkerName = auth()->user()->satker->nama_satker ?? '-';

        try {
            $filePath = $request->file('file')->getRealPath();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

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
                    'message' => 'Header NOPOL tidak ditemukan. Pastikan file memiliki kolom "NOPOL".',
                ], 422);
            }

            // Build column map from header row
            $colMap = [];
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                    $colMap['nopol'] = $col;
                } elseif (in_array($val, ['jenis kendaraan', 'jenis_kendaraan', 'jenis', 'tipe', 'tipe kendaraan', 'tipe_kendaraan'])) {
                    $colMap['jenis_kendaraan'] = $col;
                } elseif (in_array($val, ['jenis bbm', 'jenis_bbm', 'bbm', 'bahan bakar'])) {
                    $colMap['jenis_bbm'] = $col;
                }
            }

            Log::info('Satker Import Preview: headerRow=' . $headerRow . ', colMap=' . json_encode($colMap) . ', totalRows=' . $highestRow);

            $newEntries = [];
            $duplicates = [];
            $errors = [];
            $successCount = 0;

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nopol = isset($colMap['nopol']) ? trim((string) $sheet->getCell($colMap['nopol'] . $r)->getValue()) : '';
                $jenisKendaraan = isset($colMap['jenis_kendaraan']) ? trim((string) $sheet->getCell($colMap['jenis_kendaraan'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : '';

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
                    $duplicates[] = [
                        'row' => $r, 'no_polisi' => $nopol, 'changes' => $changes, 'has_changes' => count($changes) > 0,
                    ];
                } else {
                    $newEntries[] = [
                        'row' => $r, 'no_polisi' => $nopol, 'jenis_kendaraan' => $jenisKendaraan,
                        'jenis_bbm' => $jenisBbm, 'satker' => $satkerName,
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
            Log::error('Satker Import Preview Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function importKendaraan(Request $request)
    {
        $canImport = \App\Models\Setting::where('key', 'satker_can_import_kendaraan')->value('value') ?? '1';
        if ($canImport == '0') {
            return back()->with('error', 'Fitur import kendaraan saat ini dinonaktifkan oleh Administrator.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'duplicate_action' => 'required|in:skip,update',
        ], [
            'file.required' => 'File Excel harus dipilih.',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'duplicate_action.required' => 'Pilih aksi untuk data duplikat.',
        ]);

        $satkerId = auth()->user()->satker_id;
        $duplicateAction = $request->input('duplicate_action', 'skip');

        try {
            $filePath = $request->file('file')->getRealPath();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

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
                return redirect()->route('satker.kendaraans.index')->with('error', 'Header NOPOL tidak ditemukan dalam file.');
            }

            // Build column map
            $colMap = [];
            foreach (range('A', $highestCol) as $col) {
                $val = strtolower(trim((string) $sheet->getCell($col . $headerRow)->getValue()));
                if (in_array($val, ['nopol', 'no polisi', 'no_polisi', 'nomor polisi'])) {
                    $colMap['nopol'] = $col;
                } elseif (in_array($val, ['jenis kendaraan', 'jenis_kendaraan', 'jenis', 'tipe', 'tipe kendaraan', 'tipe_kendaraan'])) {
                    $colMap['jenis_kendaraan'] = $col;
                } elseif (in_array($val, ['jenis bbm', 'jenis_bbm', 'bbm', 'bahan bakar'])) {
                    $colMap['jenis_bbm'] = $col;
                }
            }

            Log::info('Satker Import Kendaraan: headerRow=' . $headerRow . ', colMap=' . json_encode($colMap) . ', totalRows=' . $highestRow . ', duplicateAction=' . $duplicateAction);

            $successCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            for ($r = $headerRow + 1; $r <= $highestRow; $r++) {
                $nopol = isset($colMap['nopol']) ? trim((string) $sheet->getCell($colMap['nopol'] . $r)->getValue()) : '';
                $jenisKendaraan = isset($colMap['jenis_kendaraan']) ? trim((string) $sheet->getCell($colMap['jenis_kendaraan'] . $r)->getValue()) : '';
                $jenisBbm = isset($colMap['jenis_bbm']) ? trim((string) $sheet->getCell($colMap['jenis_bbm'] . $r)->getValue()) : '';

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
                else { $errors[] = "Baris {$r}: Jenis BBM '{$jenisBbm}' tidak valid."; continue; }

                // Check duplicate
                $existing = Kendaraan::where('no_polisi', $nopol)->first();
                if ($existing) {
                    if ($duplicateAction === 'update') {
                        $existing->update([
                            'jenis_kendaraan' => $jenisKendaraan,
                            'jenis_bbm' => $jenisBbm,
                            'satker_id' => $satkerId,
                        ]);
                        $updatedCount++;
                    } else {
                        $skippedCount++;
                    }
                } else {
                    // Auto-generate barcode, pin, kode
                    $barcode = strtoupper(Str::random(10));
                    while (Kendaraan::where('barcode', $barcode)->exists()) {
                        $barcode = strtoupper(Str::random(10));
                    }
                    $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $lastId = Kendaraan::max('id') ?? 0;
                    $kodeKendaraan = 'KND-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

                    Kendaraan::create([
                        'satker_id' => $satkerId,
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
            }

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => "Import Kendaraan: {$successCount} baru, {$updatedCount} diperbarui, {$skippedCount} dilewati",
            ]);

            $messages = [];
            if ($successCount > 0) $messages[] = "{$successCount} kendaraan baru ditambahkan";
            if ($updatedCount > 0) $messages[] = "{$updatedCount} data diperbarui";
            if ($skippedCount > 0) $messages[] = "{$skippedCount} data duplikat dilewati";

            if (count($errors) > 0) {
                $errorMessages = implode(' | ', array_slice($errors, 0, 5));
                if ($successCount > 0 || $updatedCount > 0) {
                    $message = implode(', ', $messages) . ". Ada " . count($errors) . " baris gagal: {$errorMessages}";
                    return redirect()->route('satker.kendaraans.index')->with('success', $message);
                } else {
                    return redirect()->route('satker.kendaraans.index')->with('error', 'Import gagal. ' . $errorMessages);
                }
            }

            $message = implode(', ', $messages) . '.';
            return redirect()->route('satker.kendaraans.index')->with('success', $message ?: 'Tidak ada data baru untuk diimport.');
        } catch (\Exception $e) {
            Log::error('Satker Import Kendaraan Error: ' . $e->getMessage());
            return redirect()->route('satker.kendaraans.index')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function export()
    {
        $user = auth()->user();
        $satkerName = $user->satker->nama_satker ?? 'Satker';
        $fileName = 'daftar-kendaraan-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $satkerName) . '-' . date('Y-m-d') . '.xlsx';

        LogAktivitas::create([
            'user_id' => $user->id,
            'aktivitas' => "Melakukan Export Data Kendaraan Satker: {$satkerName}"
        ]);

        return Excel::download(new \App\Exports\SatkerKendaraanExport($user->satker_id), $fileName);
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

