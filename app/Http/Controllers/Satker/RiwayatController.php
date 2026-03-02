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

        $query = TransaksiBbm::where('satker_id', $satker->id)->with(['kendaraan.satker', 'personel.satker', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter kendaraan
        if ($request->filled('kendaraan_id')) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        $transaksis = $query->latest('tanggal')->paginate($this->getPerPage($request))->withQueryString();

        $kendaraans = \App\Models\Kendaraan::where('satker_id', $satker->id)
            ->orderBy('no_polisi')
            ->get();

        // Statistik
        $statsQuery = TransaksiBbm::where('transaksi_bbms.satker_id', $satker->id);
        if ($request->filled('dari')) {
            $statsQuery->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $statsQuery->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('kendaraan_id')) {
            $statsQuery->where('kendaraan_id', $request->kendaraan_id);
        }

        $stats = [
            'total_transaksi' => (clone $statsQuery)->count(),
            'total_liter' => (clone $statsQuery)->sum('liter'),
        ];

    // Hitung total per jenis BBM
    $summaryBbm = (clone $statsQuery)
        ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as bbm, SUM(liter) as total")
        ->groupBy('bbm')
        ->pluck('total', 'bbm');

    $summaryBbm = $summaryBbm->sortKeys();

        return view('satker.riwayat.index', compact('transaksis', 'kendaraans', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        $satker = auth()->user()->satker;

        $query = TransaksiBbm::where('satker_id', $satker->id)->with(['kendaraan.satker', 'personel.satker', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter kendaraan
        if ($request->filled('kendaraan_id')) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        // Hitung Summary per Jenis BBM
        $summaryBbm = (clone $query)
            ->selectRaw("COALESCE(NULLIF(jenis_bbm, ''), 'TANPA JENIS') as bbm, SUM(liter) as total")
            ->groupBy('bbm')
            ->pluck('total', 'bbm')
            ->sortKeys();

        $transaksis = $query->latest('tanggal')->get();

        $pdf = Pdf::loadView('admin.riwayat.print', compact('transaksis', 'summaryBbm', 'satker'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-riwayat-bbm-' . date('Y-m-d_H-i') . '.pdf');
    }
}
