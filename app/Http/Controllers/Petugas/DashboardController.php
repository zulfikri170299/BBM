<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBbm;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $todayWita = Carbon::now('Asia/Makassar')->toDateString();

        $todayTransactions = TransaksiBbm::whereDate('created_at', $todayWita)
            ->count();
            
        $todayLiter = TransaksiBbm::whereDate('created_at', $todayWita)
            ->sum('liter');

        // Breakdown per Jenis BBM langsung dari transaksi_bbms hari ini
        $breakdownBbm = TransaksiBbm::whereDate('created_at', $todayWita)
            ->select('jenis_bbm', DB::raw('SUM(liter) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm')
            ->toArray();

        // Hutang Stats
        $hutangPerBbm = \App\Models\Hutang::where('status', 'belum_dibayar')
            ->select('jenis_bbm', DB::raw('SUM(jumlah_bon) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        // Tank Stock Stats
        $tankStock = \App\Models\SinkronisasiBbm::latest('created_at')->first();
        if ($tankStock) {
            $tankStock->pemakaian_pertamax = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $tankStock->created_at)
                ->sum('liter');

            // Tambahkan Hutang Pertamax
            $tankStock->pemakaian_pertamax += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamax')->orWhere('jenis_bbm', 'PERTAMAX');
                })
                ->where('created_at', '>=', $tankStock->created_at)
                ->sum('jumlah_bon');

            $tankStock->pemakaian_dex = TransaksiBbm::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $tankStock->created_at)
                ->sum('liter');

            // Tambahkan Hutang Dex
            $tankStock->pemakaian_dex += \App\Models\Hutang::where(function($q) {
                    $q->where('jenis_bbm', 'Pertamina Dex')->orWhere('jenis_bbm', 'PERTAMINA DEX');
                })
                ->where('created_at', '>=', $tankStock->created_at)
                ->sum('jumlah_bon');

            $tankStock->sisa_pertamax = $tankStock->stok_awal_pertamax - $tankStock->pemakaian_pertamax;
            $tankStock->sisa_dex = $tankStock->stok_awal_dex - $tankStock->pemakaian_dex;
        }

        return view('petugas.dashboard', compact('todayTransactions', 'todayLiter', 'breakdownBbm', 'hutangPerBbm', 'tankStock'));
    }
}
