<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBbm;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:pdf,excel',
        ]);

        $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Makassar')
            ->startOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $endUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Makassar')
            ->endOfDay()->setTimezone('UTC')->format('Y-m-d H:i:s');

        $transaksi = TransaksiBbm::with(['kendaraan.satker', 'petugas'])
            ->whereBetween('created_at', [$startUtc, $endUtc])
            ->latest()
            ->get();

        if ($request->type === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf', compact('transaksi', 'request'));
            return $pdf->download('laporan-transaksi-' . $request->start_date . '-to-' . $request->end_date . '.pdf');
        } else {
            return Excel::download(new TransaksiExport($transaksi), 'laporan-transaksi-' . $request->start_date . '-to-' . $request->end_date . '.xlsx');
        }
    }
}
