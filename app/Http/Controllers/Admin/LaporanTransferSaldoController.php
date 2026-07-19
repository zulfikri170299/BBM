<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTransferSaldoPersonel;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanTransferSaldoController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatTransferSaldoPersonel::with(['satker', 'tujuanKendaraan', 'kendaraan', 'personel'])
            ->latest();

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endDate);
        }

        $riwayat = $query->paginate(20)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.laporan_transfer_saldo.index', compact('riwayat', 'satkers'));
    }

    public function print(Request $request)
    {
        $query = RiwayatTransferSaldoPersonel::with(['satker', 'tujuanKendaraan', 'kendaraan', 'personel'])
            ->latest();

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }


        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startDate);
        }
        if ($request->filled('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endDate);
        }

        $riwayat = $query->get();

        $pdf = Pdf::loadView('admin.laporan_transfer_saldo.print', compact('riwayat'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-transfer-saldo-' . date('Y-m-d_H-i') . '.pdf');
    }
}
