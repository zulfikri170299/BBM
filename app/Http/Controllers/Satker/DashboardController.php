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
        $queryTransaksi = TransaksiBbm::query();

        if ($user->role !== 'super_admin') {
            $queryKendaraan->where('satker_id', $satkerId);
            $queryTransaksi->where('satker_id', $satkerId);
        }

        $totalKendaraan = $queryKendaraan->count();
        $totalPersonel = 0;
        $totalTransaksi = $queryTransaksi->count();
        $totalLiterTransaksi = (clone $queryTransaksi)->sum('liter') ?: 0;

        // Rincian per jenis roda (1 single query groupBy)
        $qRoda = Kendaraan::query();
        if ($user->role !== 'super_admin') {
            $qRoda->where('satker_id', $satkerId);
        }
        $rodaCounts = (clone $qRoda)
            ->selectRaw("COALESCE(roda, 'Non Kendaraan') as roda_type, COUNT(*) as aggregate")
            ->groupBy('roda_type')
            ->pluck('aggregate', 'roda_type');

        $rodaR2 = $rodaCounts['R2'] ?? 0;
        $rodaR4 = $rodaCounts['R4'] ?? 0;
        $rodaR6 = $rodaCounts['R6'] ?? 0;
        $rodaNon = $totalKendaraan - ($rodaR2 + $rodaR4 + $rodaR6);
        if ($rodaNon < 0) $rodaNon = 0;

        $querySaldoK = Kendaraan::query();
        $queryTransfer = \App\Models\RiwayatTransferSaldoPersonel::query();

        if ($user->role !== 'super_admin') {
            $querySaldoK->where('satker_id', $satkerId);
            $queryTransfer->where('satker_id', $satkerId);
        }

        $totalSaldoKendaraan = $querySaldoK->sum('saldo');
        $totalSaldoPersonel = 0;

        $totalTransfer = $queryTransfer->count();
        $totalLiterTransfer = (clone $queryTransfer)->sum('jumlah');

        $recentTransactions = (clone $queryTransaksi)
            ->with(['kendaraan'])
            ->latest()
            ->take(7)
            ->get();

        // 1 single query for 7 days aggregation instead of 28 queries in a loop
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $chartQuery = \App\Models\TransaksiBbm::whereBetween('created_at', [$startDate, $endDate]);
        if ($user->role !== 'super_admin') {
            $chartQuery->where('satker_id', $satkerId);
        }

        $chartRecords = $chartQuery
            ->selectRaw("DATE(created_at) as date_key, 
                         COUNT(*) as count, 
                         SUM(liter) as total_liter, 
                         SUM(CASE WHEN jenis_bbm LIKE '%Pertamax%' THEN liter ELSE 0 END) as pertamax,
                         SUM(CASE WHEN jenis_bbm LIKE '%Dex%' THEN liter ELSE 0 END) as dex")
            ->groupBy('date_key')
            ->get()
            ->keyBy('date_key');

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $record = $chartRecords[$key] ?? null;

            $chartData[] = [
                'date' => $date->format('d M'),
                'count' => $record ? (int)$record->count : 0,
                'liter' => $record ? round((float)$record->total_liter, 1) : 0,
                'pertamax' => $record ? round((float)$record->pertamax, 1) : 0,
                'dex' => $record ? round((float)$record->dex, 1) : 0,
            ];
        }

        // Breakdown per Jenis BBM
        $qSK = Kendaraan::select('jenis_bbm', \DB::raw('SUM(saldo) as total'))->groupBy('jenis_bbm');
        $qLT = \App\Models\RiwayatTransferSaldoPersonel::join('kendaraans', 'riwayat_transfer_saldo_personels.kendaraan_id', '=', 'kendaraans.id')
            ->select('kendaraans.jenis_bbm', \DB::raw('SUM(riwayat_transfer_saldo_personels.jumlah) as total'))
            ->groupBy('kendaraans.jenis_bbm');

        if ($user->role !== 'super_admin') {
            $qSK->where('satker_id', $satkerId);
            $qLT->where('riwayat_transfer_saldo_personels.satker_id', $satkerId);
        }

        $saldoKendaraanPerBbm = $qSK->pluck('total', 'jenis_bbm');
        $saldoPersonelPerBbm = collect();
        $literTransferPerBbm = $qLT->pluck('total', 'jenis_bbm');

        $personelAccessControl = '0';

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
            'totalKendaraan', 'totalPersonel', 'totalTransaksi', 'totalLiterTransaksi',
            'totalSaldoKendaraan', 'totalSaldoPersonel',
            'totalTransfer', 'totalLiterTransfer',
            'recentTransactions', 'chartData',
            'saldoKendaraanPerBbm', 'saldoPersonelPerBbm', 'literTransferPerBbm',
            'totalHutang', 'hutangPerBbm',
            'rodaR2', 'rodaR4', 'rodaR6', 'rodaNon',
            'personelAccessControl'
        ));
    }
}
