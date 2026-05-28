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
        $query = Hutang::with(['satker', 'petugas', 'adminBayar']);

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Jenis BBM
        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }
        
        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $this->getPerPage($request);
        $hutangs = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        $satkers = Satker::orderBy('nama_satker')->get();
        $jenisBbm = Hutang::select('jenis_bbm')->distinct()->whereNotNull('jenis_bbm')->pluck('jenis_bbm');

        return view('admin.laporan_hutang.index', compact('hutangs', 'satkers', 'jenisBbm'));
    }

    public function print(Request $request)
    {
        $query = Hutang::with(['satker', 'petugas', 'adminBayar']);

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $startUtc = Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
                ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $startUtc);
        }
        if ($request->filled('end_date')) {
            $endUtc = Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
                ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
            $query->where('created_at', '<=', $endUtc);
        }

        // Filter Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Jenis BBM
        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
        }
        
        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hutangs = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.laporan_hutang.print', compact('hutangs'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-pembayaran-hutang-' . date('Y-m-d_H-i') . '.pdf');
    }
}
