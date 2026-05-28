<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\RiwayatTopup;
use Illuminate\Http\Request;

class SaldoDialihkanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = RiwayatTopup::withoutGlobalScope('hide_developer_topup_history')
            ->with(['satker', 'kendaraan', 'user'])
            ->whereIn('metode', ['potong_saldo', 'Potong Saldo', 'POTONG_SALDO_MASAL'])
            ->where('keterangan', 'not like', '%Pelunasan bon%')
            ->where('satker_id', $user->satker_id);
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        $query->latest();

        $riwayat = $query->paginate(20)->withQueryString();

        return view('satker.saldo_dialihkan.index', compact('riwayat'));
    }

    public function print(Request $request)
    {
        $user = auth()->user();
        
        $query = RiwayatTopup::withoutGlobalScope('hide_developer_topup_history')
            ->with(['satker', 'kendaraan', 'user'])
            ->whereIn('metode', ['potong_saldo', 'Potong Saldo', 'POTONG_SALDO_MASAL'])
            ->where('keterangan', 'not like', '%Pelunasan bon%')
            ->where('satker_id', $user->satker_id);
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        $riwayat = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('satker.saldo_dialihkan.print', compact('riwayat', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-saldo-dialihkan-satker-' . date('Y-m-d') . '.pdf');
    }
}
