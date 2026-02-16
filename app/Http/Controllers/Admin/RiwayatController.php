<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiBbm;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiBbm::with(['kendaraan.satker', 'personel', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Search nopol
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->latest('tanggal')->paginate(15)->withQueryString();

        $satkers = Satker::orderBy('nama_satker')->get();

        // Statistik
        $statsQuery = TransaksiBbm::query();
        if ($request->filled('dari')) {
            $statsQuery->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $statsQuery->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('satker_id')) {
            $statsQuery->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        $stats = [
            'total_transaksi' => (clone $statsQuery)->count(),
            'total_liter' => (clone $statsQuery)->sum('liter'),
        ];

        // Hitung total per jenis BBM
        $summaryBbm = (clone $statsQuery)
            ->join('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw('kendaraans.jenis_bbm, SUM(transaksi_bbms.liter) as total')
            ->groupBy('kendaraans.jenis_bbm')
            ->orderBy('kendaraans.jenis_bbm')
            ->pluck('total', 'kendaraans.jenis_bbm');

        return view('admin.riwayat.index', compact('transaksis', 'satkers', 'stats', 'summaryBbm'));
    }

    public function print(Request $request)
    {
        $query = TransaksiBbm::with(['kendaraan.satker', 'personel', 'petugas']);

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Search nopol (optional for print, but good consistency)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%");
            });
        }

        // Hitung Summary per Jenis BBM
        $summaryBbm = (clone $query)->join('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw('kendaraans.jenis_bbm, SUM(transaksi_bbms.liter) as total')
            ->groupBy('kendaraans.jenis_bbm')
            ->orderBy('kendaraans.jenis_bbm')
            ->pluck('total', 'kendaraans.jenis_bbm');

        $transaksis = $query->latest('tanggal')->get();

        $pdf = Pdf::loadView('admin.riwayat.print', compact('transaksis', 'summaryBbm'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-riwayat-bbm-' . date('Y-m-d_H-i') . '.pdf');
    }
}
