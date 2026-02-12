<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBbm;

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

        return view('petugas.dashboard', compact('todayTransactions', 'todayLiter'));
    }
}
