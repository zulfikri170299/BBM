<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTopup;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\PaginatesTables;

class LaporanTopupController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = RiwayatTopup::with(['kendaraan', 'user', 'satker'])->orderBy('riwayat_topups.created_at', 'desc');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('riwayat_topups.created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('riwayat_topups.created_at', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('riwayat_topups.satker_id', $request->satker_id);
        }

        // Hitung Summary per Jenis BBM
        $summary = (clone $query)->reorder()->join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw("kendaraans.jenis_bbm as jenis_bbm, SUM(CASE WHEN riwayat_topups.tipe = 'masuk' THEN riwayat_topups.jumlah ELSE -riwayat_topups.jumlah END) as total")
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $perPage = $this->getPerPage($request);
        $riwayats = $query->latest()->paginate($perPage)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.laporan_topup.index', compact('riwayats', 'satkers', 'summary'));
    }

    public function print(Request $request)
    {
        $query = RiwayatTopup::with(['kendaraan', 'user', 'satker'])->orderBy('riwayat_topups.created_at', 'desc');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('riwayat_topups.created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('riwayat_topups.created_at', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('riwayat_topups.satker_id', $request->satker_id);
        }

        // Hitung Summary per Jenis BBM
        $summary = (clone $query)->reorder()->join('kendaraans', 'riwayat_topups.kendaraan_id', '=', 'kendaraans.id')
            ->selectRaw("kendaraans.jenis_bbm as jenis_bbm, SUM(CASE WHEN riwayat_topups.tipe = 'masuk' THEN riwayat_topups.jumlah ELSE -riwayat_topups.jumlah END) as total")
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $riwayats = $query->get(); // Get all data without pagination

        $pdf = Pdf::loadView('admin.laporan_topup.print', compact('riwayats', 'summary'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-topup-' . date('Y-m-d_H-i') . '.pdf');
    }
}
