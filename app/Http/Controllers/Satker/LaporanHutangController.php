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
        $user = auth()->user();
        $satker = $user->satker;
        $query = Hutang::with(['petugas', 'adminBayar']);
        
        if ($user->role !== 'super_admin') {
            $query->where('satker_id', $satker->id);
        }

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

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $this->getPerPage($request);
        
        // Hitung total per jenis BBM sebelum paginasi
        $totalPertamax = (clone $query)->where('jenis_bbm', 'Pertamax')->sum('jumlah_bon');
        $totalDex = (clone $query)->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah_bon');
        
        $hutangs = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('satker.laporan_hutang.index', compact('hutangs', 'totalPertamax', 'totalDex'));
    }

    public function print(Request $request)
    {
        $user = auth()->user();
        $satker = $user->satker;
        $query = Hutang::with(['petugas', 'adminBayar']);
        
        if ($user->role !== 'super_admin') {
            $query->where('satker_id', $satker->id);
        }

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

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hutangs = $query->orderBy('created_at', 'desc')->get();

        $totalPertamax = $hutangs->where('jenis_bbm', 'Pertamax')->sum('jumlah_bon');
        $totalDex = $hutangs->where('jenis_bbm', 'Pertamina Dex')->sum('jumlah_bon');

        $pdf = Pdf::loadView('satker.laporan_hutang.print', compact('hutangs', 'satker', 'totalPertamax', 'totalDex'))
            ->setPaper([0, 0, 609.45, 935.43], 'landscape'); // F4 (215mm x 330mm)

        return $pdf->stream('laporan-hutang-' . date('Y-m-d_H-i') . '.pdf');
    }
}
