<?php

namespace App\Http\Controllers\Personel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBbm;
use App\Models\Kendaraan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $personel = $user->personel;
        
        // If not linked to a personel record, show basic info or empty state
        if (!$personel) {
            return view('personel.dashboard', [
                'saldo' => 0,
                'transactions' => [],
                'kendaraans' => collect([]),
                'error' => 'Akun Anda belum terhubung dengan data Personel.'
            ]);
        }

        $saldo = $personel->saldo;
        $transactions = TransaksiBbm::where('personel_id', $personel->id) // Assuming we add personel_id to transaction later or if schema supports it
                        // Wait, transaksi_bbm has personel_id?
                        // Schema: kendaraan_id, petugas_id... let's check migration.
                        // 2026_02_12_021611_create_transaksi_bbms_table.php
                        // $table->foreignId('personel_id')->nullable()->constrained('personels');
                        ->with(['kendaraan', 'petugas'])
                        ->latest()
                        ->paginate(10);

        // Satker Vehicles
        $kendaraans = Kendaraan::where('satker_id', $user->satker_id)->get();

        $kendaraans = Kendaraan::where('satker_id', $user->satker_id)->get();

        return view('personel.dashboard', compact('saldo', 'transactions', 'kendaraans'));
    }
}
