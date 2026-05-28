<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\Satker;
use App\Models\User;
use App\Models\Personel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FuelTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_fuel_transaction_flow()
    {
        // 1. Super Admin creates Satker and Admin Satker
        $this->seed(); // Runs DatabaseSeeder which sets up Satker, Super Admin, Admin Satker, Petugas, Personel

        $satker = Satker::first();
        $adminSatker = User::where('role', 'admin_satker')->first();
        $petugas = User::where('role', 'petugas_bbm')->first();
        $personelUser = User::where('role', 'personel')->first();

        // 2. Admin Satker adds a Vehicle
        $response = $this->actingAs($adminSatker)->post(route('satker.kendaraans.store'), [
            'no_polisi' => 'B 1234 TEST',
            'jenis_kendaraan' => 'Minibus',
            'jenis_bbm' => 'Pertalite',
            'pin' => '123456',
        ]);
        $response->assertRedirect(route('satker.kendaraans.index'));

        $kendaraan = Kendaraan::where('no_polisi', 'B 1234 TEST')->first();
        $this->assertNotNull($kendaraan);
        $this->assertNotNull($kendaraan->barcode);

        // 3. Super Admin tops up balance (Global Topup for simplicity in test, or Admin Satker logic if implemented)
        // Let's manually set balance for test since TopUp UI is global
        $kendaraan->update(['saldo' => 100000]);

        // 4. Petugas checks vehicle via Barcode
        $response = $this->actingAs($petugas)->post(route('petugas.transaksi.check'), [
            'barcode' => $kendaraan->barcode,
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('petugas.transaksi.create');

        // 5. Petugas processes transaction
        // Price Pertalite = 10000. 5 Liters = 50000.
        $response = $this->actingAs($petugas)->post(route('petugas.transaksi.process'), [
            'kendaraan_id' => $kendaraan->id,
            'liter' => 5,
            'pin' => '123456',
        ]);
        
        $response->assertStatus(200); // Because it returns PDF stream directly or redirect?
        // Controller returns: return $pdf->stream(...); which is 200 OK with PDF content.
        
        // 6. Verify Deduction
        $kendaraan->refresh();
        $this->assertEquals(50000, $kendaraan->saldo); // 100000 - 50000

        // 7. Personel View (Optional, if linked)
        // In seeder, Budi Santoso is linked to Personel User.
        // But this vehicle is not 'owned' by Budi personally, it's Satker vehicle.
        // However, personels can see Satker vehicles in dashboard.
        $response = $this->actingAs($personelUser)->get(route('personel.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('B 1234 TEST');
        $response->assertSee('50,000'); // Sisa saldo
    }
}
