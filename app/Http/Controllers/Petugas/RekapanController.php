<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\DailyMeterReading; // Gunakan model ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapanController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->input('month', Carbon::now()->month);
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        $readings = DailyMeterReading::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate Grand Totals based on filtered data
        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($readings as $reading) {
            $liter = max(0, $reading->meter_akhir - $reading->meter_awal);
            if (in_array($reading->jenis_bbm, ['Pertamax', 'PERTAMAX'])) {
                $totalPertamax += $liter;
            } elseif (in_array($reading->jenis_bbm, ['Pertamina Dex', 'PERTAMINA DEX'])) {
                $totalDex += $liter;
            }
        }

        // Grouping data: Tanggal -> Jenis BBM
        $recap = $readings->groupBy('tanggal');

        return view('petugas.rekapan.index', compact('recap', 'selectedMonth', 'selectedYear', 'totalPertamax', 'totalDex'));
    }

    public function print(Request $request)
    {
        $selectedMonth = (int) $request->input('month', Carbon::now()->month);
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        $readings = DailyMeterReading::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->orderBy('tanggal', 'asc') // Create chronological order for report
            ->get();

        $totalPertamax = 0;
        $totalDex = 0;

        foreach ($readings as $reading) {
            $liter = max(0, $reading->meter_akhir - $reading->meter_awal);
            if (in_array($reading->jenis_bbm, ['Pertamax', 'PERTAMAX'])) {
                $totalPertamax += $liter;
            } elseif (in_array($reading->jenis_bbm, ['Pertamina Dex', 'PERTAMINA DEX'])) {
                $totalDex += $liter;
            }
        }

        $recap = $readings->groupBy('tanggal');

        return view('petugas.rekapan.print', compact('recap', 'selectedMonth', 'selectedYear', 'totalPertamax', 'totalDex'));
    }
}
