<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyMeterReading;
use Carbon\Carbon;

class MeterReadingController extends Controller
{
    public function index(Request $request)
    {
        $today = $request->input('tanggal', Carbon::now('Asia/Makassar')->toDateString());
        $readings = DailyMeterReading::where('tanggal', $today)->get()->keyBy('jenis_bbm');
        
        $lastReadings = [];
        foreach (['PERTAMAX', 'PERTAMINA DEX'] as $bbm) {
            $last = DailyMeterReading::where('jenis_bbm', $bbm)
                ->where('tanggal', '<', $today)
                ->orderBy('tanggal', 'desc')
                ->first();
            $lastReadings[$bbm] = $last;
        }

        $recentReadings = DailyMeterReading::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        
        return view('petugas.meter.index', compact('today', 'readings', 'lastReadings', 'recentReadings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_bbm' => 'required|string',
            'meter_awal' => 'nullable|integer|min:0',
            'meter_akhir' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DailyMeterReading::updateOrCreate(
            ['tanggal' => $request->tanggal, 'jenis_bbm' => $request->jenis_bbm],
            [
                'meter_awal' => $request->meter_awal ?? 0, 
                'meter_akhir' => $request->meter_akhir ?? 0,
                'keterangan' => $request->keterangan
            ]
        );

        return back()->with('success', 'Data meteran ' . $request->jenis_bbm . ' berhasil disimpan.');
    }
}
