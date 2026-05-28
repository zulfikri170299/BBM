<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTopup;
use Illuminate\Http\Request;

class SaldoDialihkanController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatTopup::withoutGlobalScope('hide_developer_topup_history')
            ->with(['satker', 'kendaraan', 'user'])
            ->whereIn('metode', ['potong_saldo', 'Potong Saldo', 'POTONG_SALDO_MASAL'])
            ->where('keterangan', 'not like', '%Pelunasan bon%');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }
        
        $query->latest();

        $riwayat = $query->paginate(20)->withQueryString();
        $satkers = \App\Models\Satker::orderBy('nama_satker')->get();

        return view('admin.saldo_dialihkan.index', compact('riwayat', 'satkers'));
    }

    public function print(Request $request)
    {
        $query = RiwayatTopup::withoutGlobalScope('hide_developer_topup_history')
            ->with(['satker', 'kendaraan', 'user'])
            ->whereIn('metode', ['potong_saldo', 'Potong Saldo', 'POTONG_SALDO_MASAL'])
            ->where('keterangan', 'not like', '%Pelunasan bon%');
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }
        
        $riwayat = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.saldo_dialihkan.print', compact('riwayat', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-saldo-dialihkan-admin-' . date('Y-m-d') . '.pdf');
    }
}
