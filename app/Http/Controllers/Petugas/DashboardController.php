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

        // Breakdown per Jenis BBM
        $literKendaraan = TransaksiBbm::whereDate('transaksi_bbms.created_at', $todayWita)
            ->whereNotNull('kendaraan_id')
            ->join('kendaraans', 'transaksi_bbms.kendaraan_id', '=', 'kendaraans.id')
            ->select('kendaraans.jenis_bbm', DB::raw('SUM(transaksi_bbms.liter) as total'))
            ->groupBy('kendaraans.jenis_bbm')
            ->get();

        $literPersonel = TransaksiBbm::whereDate('transaksi_bbms.created_at', $todayWita)
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
        $adminTransactions = TransaksiBbm::whereDate('created_at', $todayWita)
            ->whereHas('petugas', function($q) {
                $q->whereIn('role', ['super_admin', 'kasubbag', 'admin_satker']);
            })
            ->select('jenis_bbm', DB::raw('SUM(liter) as total_liter'), DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('jenis_bbm')
            ->get();

        return view('petugas.dashboard', compact('todayTransactions', 'todayLiter', 'breakdownBbm', 'hutangPerBbm', 'tankStock', 'adminTransactions'));
    }
}
