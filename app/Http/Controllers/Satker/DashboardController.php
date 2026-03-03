<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Personel;
use App\Models\TransaksiBbm;

class DashboardController extends Controller
{
    public function index()
    {
        $satkerId = auth()->user()->satker_id;

        $totalKendaraan = Kendaraan::where('satker_id', $satkerId)->count();
        $totalPersonel = Personel::where('satker_id', $satkerId)->count();
        $totalTransaksi = TransaksiBbm::whereHas('kendaraan', function($q) use ($satkerId) {
            $q->where('satker_id', $satkerId);
        })->count();

        $totalSaldoKendaraan = Kendaraan::where('satker_id', $satkerId)->sum('saldo');
        $totalSaldoPersonel = Personel::where('satker_id', $satkerId)->sum('saldo');

        $totalTransfer = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)->count();
        $totalLiterTransfer = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)->sum('jumlah');

        $recentTransfers = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
            ->with(['kendaraan', 'personel'])
            ->latest()
            ->take(7)
            ->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->whereDate('created_at', $date)->count();
            $liter = \App\Models\RiwayatTransferSaldoPersonel::where('satker_id', $satkerId)
                ->whereDate('created_at', $date)->sum('jumlah');
            $chartData[] = ['date' => $date->format('d M'), 'count' => $count, 'liter' => round($liter, 1)];
        }

        // Breakdown per Jenis BBM
        $saldoKendaraanPerBbm = Kendaraan::where('satker_id', $satkerId)
            ->select('jenis_bbm', \DB::raw('SUM(saldo) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $saldoPersonelPerBbm = Personel::where('satker_id', $satkerId)
            ->select('jenis_bbm', \DB::raw('SUM(saldo) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        $literTransferPerBbm = \App\Models\RiwayatTransferSaldoPersonel::where('riwayat_transfer_saldo_personels.satker_id', $satkerId)
            ->join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('kendaraans.jenis_bbm', \DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->groupBy('kendaraans.jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        // Hutang Stats
        $totalHutang = \App\Models\Hutang::where('satker_id', $satkerId)
            ->where('status', 'belum_dibayar')
            ->sum('jumlah_bon');

        $hutangPerBbm = \App\Models\Hutang::where('satker_id', $satkerId)
            ->where('status', 'belum_dibayar')
            ->select('jenis_bbm', \DB::raw('SUM(jumlah_bon) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('satker.dashboard', compact(
            'totalKendaraan', 'totalPersonel', 'totalTransaksi',
            'totalSaldoKendaraan', 'totalSaldoPersonel',
            'totalTransfer', 'totalLiterTransfer',
            'recentTransfers', 'chartData',
            'saldoKendaraanPerBbm', 'saldoPersonelPerBbm', 'literTransferPerBbm',
            'totalHutang', 'hutangPerBbm'
        ));
    }
}
