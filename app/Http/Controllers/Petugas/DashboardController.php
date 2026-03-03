<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBbm;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTransactions = TransaksiBbm::where('petugas_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();
            
        $todayLiter = TransaksiBbm::where('petugas_id', auth()->id())
            ->whereDate('created_at', today())
            ->sum('liter');

        // Breakdown per Jenis BBM
        $literKendaraan = TransaksiBbm::where('petugas_id', auth()->id())
            ->whereDate('transaksi_bbms.created_at', today())
            ->whereNotNull('kendaraan_id')
            ->join('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->select('kendaraans.jenis_bbm', DB::raw('SUM(transaksi_bbms.liter) as total'))
            ->groupBy('kendaraans.jenis_bbm')
            ->get();

        $literPersonel = TransaksiBbm::where('petugas_id', auth()->id())
            ->whereDate('transaksi_bbms.created_at', today())
            ->whereNotNull('personel_id')
            ->join('personels', 'transaksi_bbms.personel_id', '=', 'personels.id')
            ->select('personels.jenis_bbm', DB::raw('SUM(transaksi_bbms.liter) as total'))
            ->groupBy('personels.jenis_bbm')
            ->get();

        // Gabungkan hasil
        $breakdownBbm = [];
        foreach ($literKendaraan as $item) {
            $breakdownBbm[$item->jenis_bbm] = ($breakdownBbm[$item->jenis_bbm] ?? 0) + $item->total;
        }
        foreach ($literPersonel as $item) {
            $breakdownBbm[$item->jenis_bbm] = ($breakdownBbm[$item->jenis_bbm] ?? 0) + $item->total;
        }

        // Hutang Stats
        $hutangPerBbm = \App\Models\Hutang::where('status', 'belum_dibayar')
            ->select('jenis_bbm', DB::raw('SUM(jumlah_bon) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('petugas.dashboard', compact('todayTransactions', 'todayLiter', 'breakdownBbm', 'hutangPerBbm'));
    }
}
