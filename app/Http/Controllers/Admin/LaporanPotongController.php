<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTopup;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPotongController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatTopup::with(['satker', 'kendaraan', 'user'])
            ->where('metode', 'potong_saldo')
            ->latest();

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Tanggal
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

        $riwayat = $query->paginate(20)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.kendaraans.laporan-potong', compact('riwayat', 'satkers'));
    }

    public function print(Request $request)
    {
        $query = RiwayatTopup::with(['satker', 'kendaraan', 'user'])
            ->where('metode', 'potong_saldo')
            ->latest();

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Tanggal
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

        $riwayat = $query->get();

        $pdf = Pdf::loadView('admin.kendaraans.laporan-potong-pdf', compact('riwayat'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-potong-saldo-' . date('Y-m-d_H-i') . '.pdf');
    }
}
