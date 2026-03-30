<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\TransaksiBbm;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class RiwayatController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $satker = auth()->user()->satker;

        // 1. Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::where('satker_id', $satker->id)->with(['kendaraan.satker', 'personel.satker', 'petugas']);
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('kendaraan_id')) $queryTrx->where('kendaraan_id', $request->kendaraan_id);
        
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // 2. Ambil RiwayatTopup (Potong Saldo)
        $queryTopup = \App\Models\RiwayatTopup::where('satker_id', $satker->id)
            ->where('tipe', 'keluar')
            ->with(['kendaraan.satker', 'user']);
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('kendaraan_id')) $queryTopup->where('kendaraan_id', $request->kendaraan_id);

        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
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

        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satker->id)
            ->orderBy('no_polisi')
            ->get();

        // Statistik (Mengecualikan Biro Logistik/ID 1 karena dianggap data pengujian/potong saldo)
        $trxs_murni = $trxs->where('satker_id', '!=', 1);
        $totalLiterMurni = $trxs_murni->sum('liter');

        $stats = [
            'total_transaksi' => $merged->count(),
            'total_liter' => $totalLiterMurni,
        ];

        // Summary per Jenis BBM (Hanya dari pengisian murni di luar Biro Logistik)
        $summaryBbm = $trxs_murni->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

        return view('satker.riwayat.index', compact('transaksis', 'kendaraans', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        $satker = auth()->user()->satker;

        // Ambil TransaksiBbm
        $queryTrx = TransaksiBbm::where('satker_id', $satker->id)->with(['kendaraan.satker', 'personel.satker', 'petugas']);
        if ($request->filled('dari')) $queryTrx->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTrx->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('kendaraan_id')) $queryTrx->where('kendaraan_id', $request->kendaraan_id);
        $trxs = $queryTrx->get()->map(function($item) {
            $item->row_type = 'pengisian';
            $item->sort_date = $item->tanggal;
            return $item;
        });

        // Ambil RiwayatTopup (keluar)
        $queryTopup = \App\Models\RiwayatTopup::where('satker_id', $satker->id)->where('tipe', 'keluar')->with(['kendaraan.satker', 'user']);
        if ($request->filled('dari')) $queryTopup->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $queryTopup->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('kendaraan_id')) $queryTopup->where('kendaraan_id', $request->kendaraan_id);
        $topups = $queryTopup->get()->map(function($item) {
            $item->row_type = 'potong_saldo';
            $item->sort_date = $item->created_at;
            $item->tanggal = $item->created_at;
            $item->liter = $item->jumlah;
            $item->nama_driver = "POTONG SALDO";
            return $item;
        });

        $transaksis = $trxs->concat($topups)->sortByDesc('sort_date')->values();

        // Hitung Summary per Jenis BBM (Hanya dari pengisian murni di luar Biro Logistik)
        $summaryBbm = $trxs->where('satker_id', '!=', 1)->groupBy('jenis_bbm')->map(function ($group) {
            return $group->sum('liter');
        })->sortKeys();

        $pdf = Pdf::loadView('admin.riwayat.print', compact('transaksis', 'summaryBbm', 'satker'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-riwayat-bbm-' . date('Y-m-d_H-i') . '.pdf');
    }
}
