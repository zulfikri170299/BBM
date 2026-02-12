<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function topup()
    {
        return view('admin.topup');
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $amount = $request->amount;
        $count = \App\Models\Kendaraan::count();

        \App\Models\Kendaraan::query()->increment('saldo', $amount);

        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Top-up massal sebesar Rp " . number_format($amount) . " untuk $count kendaraan."
        ]);

        return redirect()->route('admin.topup')->with('success', "Berhasil top-up Rp " . number_format($amount) . " untuk $count kendaraan.");
    }
}
