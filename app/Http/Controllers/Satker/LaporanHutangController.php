<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\Hutang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PaginatesTables;
use Carbon\Carbon;

class LaporanHutangController extends Controller
{
    use PaginatesTables;

    public function index(Request $request)
    {
        $satker = auth()->user()->satker;
        $query = Hutang::with(['petugas', 'adminBayar'])
            ->where('satker_id', $satker->id)
            ->where('status', 'sudah_dibayar');

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

        $perPage = $this->getPerPage($request);
        $hutangs = $query->orderBy('tanggal_bayar', 'desc')->paginate($perPage)->withQueryString();

        return view('satker.laporan_hutang.index', compact('hutangs'));
    }

    public function print(Request $request)
    {
        $satker = auth()->user()->satker;
        $query = Hutang::with(['petugas', 'adminBayar'])
            ->where('satker_id', $satker->id)
            ->where('status', 'sudah_dibayar');

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

        $hutangs = $query->orderBy('tanggal_bayar', 'desc')->get();

        $pdf = Pdf::loadView('satker.laporan_hutang.print', compact('hutangs', 'satker'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-pembayaran-hutang-' . date('Y-m-d_H-i') . '.pdf');
    }
}
