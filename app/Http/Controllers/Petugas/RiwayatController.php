<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class RiwayatController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        // 1. Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);
        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        if ($personelAccessControl == '0') {
            $queryTrx->whereNull('personel_id');
        }
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTrx->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTrx->where(function ($q) use ($search) {
                $q->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); })
                  ->orWhereHas('personel', function ($sub) use ($search) { $sub->where('nama', 'like', "%$search%"); });
            });
        }
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // 2. Ambil RiwayatTopup (Hanya yang keluar / potong saldo)
        $queryTopup = \App\Models\RiwayatTopup::with(['satker', 'kendaraan.satker', 'user'])
            ->where('tipe', 'keluar');
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTopup->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTopup->whereHas('kendaraan', function ($sub) use ($search) {
                $sub->where('no_polisi', 'like', "%$search%");
            });
        }
        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
            // Map fields to match TransaksiBbm for view consistency
            $item->tanggal = $item->created_at;
            $item->liter = $item->jumlah;
            $item->nama_driver = "POTONG SALDO";
            return $item;
        });

        // 3. Gabungkan dan Urutkan
        $merged = $trxs->concat($topups)->sortByDesc('sort_date')->values();

        // 4. Paginate Manual
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = $this->getPerPage($request);
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $transaksis = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $merged->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        $satkers = Satker::orderBy('nama_satker')->get();

        // Statistik (Tampilkan semua data sesuai filter)
        $totalLiterMurni = $trxs->sum('liter');
        
        $stats = [
            'total_transaksi' => $merged->count(),
            'total_liter' => $totalLiterMurni,
        ];

        // Summary per Jenis BBM
        $summaryBbm = $trxs->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

        return view('petugas.riwayat.index', compact('transaksis', 'satkers', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        // Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::with(['satker', 'kendaraan.satker', 'personel', 'petugas']);
        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        if ($personelAccessControl == '0') {
            $queryTrx->whereNull('personel_id');
        }
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTrx->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTrx->where(function ($q) use ($search) {
                $q->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); })
                  ->orWhereHas('personel', function ($sub) use ($search) { $sub->where('nama', 'like', "%$search%"); });
            });
        }
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // Ambil RiwayatTopup (keluar)
        $queryTopup = \App\Models\RiwayatTopup::with(['satker', 'kendaraan.satker', 'user'])->where('tipe', 'keluar');
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('satker_id')) $queryTopup->where('satker_id', $request->satker_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $queryTopup->whereHas('kendaraan', function ($sub) use ($search) { $sub->where('no_polisi', 'like', "%$search%"); });
        }
        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
            $item->tanggal = $item->created_at;
            $item->liter = $item->jumlah;
            $item->nama_driver = "POTONG SALDO";
            return $item;
        });

        $transaksis = $trxs->concat($topups)->sortByDesc('sort_date')->values();

        $summaryBbm = $trxs->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

        $pdf = Pdf::loadView('petugas.riwayat.print', compact('transaksis', 'summaryBbm'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-riwayat-bbm-' . date('Y-m-d_H-i') . '.pdf');
    }
}
