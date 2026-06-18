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
        $user = auth()->user();
        $satkerId = $user->satker_id;

        $queryKendaraan = Kendaraan::query();
        $queryPersonel = Personel::query();
        $queryTransaksi = TransaksiBbm::query();

        if ($user->role !== 'super_admin') {
            $queryKendaraan->where('satker_id', $satkerId);
            $queryPersonel->where('satker_id', $satkerId);
            $queryTransaksi->whereHas('kendaraan', function($q) use ($satkerId) {
                $q->where('satker_id', $satkerId);
            });
        }

        $totalKendaraan = $queryKendaraan->count();
        $totalPersonel = $queryPersonel->count();
        $totalTransaksi = $queryTransaksi->count();

        // Rincian per jenis roda
        $qRoda = Kendaraan::query();
        if ($user->role !== 'super_admin') {
            $qRoda->where('satker_id', $satkerId);
        }
        $rodaR2 = (clone $qRoda)->where('roda', 'R2')->count();
        $rodaR4 = (clone $qRoda)->where('roda', 'R4')->count();
        $rodaR6 = (clone $qRoda)->where('roda', 'R6')->count();
        $rodaNon = (clone $qRoda)->where('roda', 'Non Kendaraan')->count();

        $querySaldoK = Kendaraan::query();
        $querySaldoP = Personel::query();
        $queryTransfer = \App\Models\RiwayatTransferSaldoPersonel::query();

        if ($user->role !== 'super_admin') {
            $querySaldoK->where('satker_id', $satkerId);
            $querySaldoP->where('satker_id', $satkerId);
            $queryTransfer->where('satker_id', $satkerId);
        }

        $totalSaldoKendaraan = $querySaldoK->sum('saldo');
        $totalSaldoPersonel = $querySaldoP->sum('saldo');

        $totalTransfer = $queryTransfer->count();
        $totalLiterTransfer = (clone $queryTransfer)->sum('jumlah');

        $recentTransfers = (clone $queryTransfer)
            ->with(['kendaraan', 'personel'])
            ->latest()
            ->take(7)
            ->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $qChart = \App\Models\RiwayatTransferSaldoPersonel::whereDate('created_at', $date);
            if ($user->role !== 'super_admin') {
                $qChart->where('satker_id', $satkerId);
            }
            $count = (clone $qChart)->count();
            $liter = (clone $qChart)->sum('jumlah');
            $chartData[] = ['date' => $date->format('d M'), 'count' => $count, 'liter' => round($liter, 1)];
        }

        // Breakdown per Jenis BBM
        $qSK = Kendaraan::select('jenis_bbm', \DB::raw('SUM(saldo) as total'))->groupBy('jenis_bbm');
        $qSP = Personel::select('jenis_bbm', \DB::raw('SUM(saldo) as total'))->groupBy('jenis_bbm');
        $qLT = \App\Models\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('kendaraans.jenis_bbm', \DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->groupBy('kendaraans.jenis_bbm');

        if ($user->role !== 'super_admin') {
            $qSK->where('satker_id', $satkerId);
            $qSP->where('satker_id', $satkerId);
            $qLT->where('riwayat_transfer_saldo_personels.satker_id', $satkerId);
        }

        $saldoKendaraanPerBbm = $qSK->pluck('total', 'jenis_bbm');
        $saldoPersonelPerBbm = $qSP->pluck('total', 'jenis_bbm');
        $literTransferPerBbm = $qLT->pluck('total', 'jenis_bbm');

        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        if ($personelAccessControl == '0') {
            $totalPersonel = 0;
            $totalSaldoPersonel = 0;
            $saldoPersonelPerBbm = collect();
        }

        // Hutang Stats
        $qH = \App\Models\Hutang::where('status', 'belum_dibayar');
        if ($user->role !== 'super_admin') {
            $qH->where('satker_id', $satkerId);
        }

        $totalHutang = (clone $qH)->sum('jumlah_bon');
        $hutangPerBbm = (clone $qH)
            ->select('jenis_bbm', \DB::raw('SUM(jumlah_bon) as total'))
            ->groupBy('jenis_bbm')
            ->pluck('total', 'jenis_bbm');

        return view('satker.dashboard', compact(
            'totalKendaraan', 'totalPersonel', 'totalTransaksi',
            'totalSaldoKendaraan', 'totalSaldoPersonel',
            'totalTransfer', 'totalLiterTransfer',
            'recentTransfers', 'chartData',
            'saldoKendaraanPerBbm', 'saldoPersonelPerBbm', 'literTransferPerBbm',
            'totalHutang', 'hutangPerBbm',
            'rodaR2', 'rodaR4', 'rodaR6', 'rodaNon',
            'personelAccessControl'
        ));
    }
}
