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

        $transaksi = TransaksiBbm::with(['kendaraan.satker', 'petugas'])
            ->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59'])
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
