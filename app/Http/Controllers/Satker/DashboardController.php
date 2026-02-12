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

        return view('satker.dashboard', compact('totalKendaraan', 'totalPersonel', 'totalTransaksi'));
    }
}
