<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTopup;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanTopupController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatTopup::with(['kendaraan.satker', 'user'])->orderBy('riwayat_topups.created_at', 'desc');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('riwayat_topups.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('riwayat_topups.created_at', '<=', $request->end_date);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Hitung Summary per Jenis BBM
        $summary = (clone $query)->reorder()->join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw('kendaraans.jenis_bbm as jenis_bbm, SUM(riwayat_topups.jumlah) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $riwayats = $query->paginate(20);
        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.laporan_topup.index', compact('riwayats', 'satkers', 'summary'));
    }

    public function print(Request $request)
    {
        $query = RiwayatTopup::with(['kendaraan.satker', 'user'])->orderBy('riwayat_topups.created_at', 'desc');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('riwayat_topups.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('riwayat_topups.created_at', '<=', $request->end_date);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('satker_id', $request->satker_id);
            });
        }

        // Hitung Summary per Jenis BBM
        $summary = (clone $query)->reorder()->join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw('kendaraans.jenis_bbm as jenis_bbm, SUM(riwayat_topups.jumlah) as total')
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $riwayats = $query->get(); // Get all data without pagination

        $pdf = Pdf::loadView('admin.laporan_topup.print', compact('riwayats', 'summary'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-topup-' . date('Y-m-d_H-i') . '.pdf');
    }
}
