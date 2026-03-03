<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hutang;
use App\Models\Satker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PaginatesTables;
use Carbon\Carbon;

class LaporanHutangController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $query = Hutang::with(['satker', 'petugas', 'adminBayar'])->where('status', 'sudah_dibayar');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('tanggal_bayar', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('tanggal_bayar', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        $perPage = $this->getPerPage($request);
        $hutangs = $query->orderBy('tanggal_bayar', 'desc')->paginate($perPage)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();

        return view('admin.laporan_hutang.index', compact('hutangs', 'satkers'));
    }

    public function print(Request $request)
    {
        $query = Hutang::with(['satker', 'petugas', 'adminBayar'])->where('status', 'sudah_dibayar');

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('tanggal_bayar', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('tanggal_bayar', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        $hutangs = $query->orderBy('tanggal_bayar', 'desc')->get();

        $pdf = Pdf::loadView('admin.laporan_hutang.print', compact('hutangs'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-pembayaran-hutang-' . date('Y-m-d_H-i') . '.pdf');
    }
}
