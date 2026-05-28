<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PembelianBbm;
use App\Models\TransaksiBbm;
use App\Models\Hutang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class DummyLaporanSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        PembelianBbm::truncate();
        TransaksiBbm::truncate();
        Hutang::truncate();
        Schema::enableForeignKeyConstraints();

        $satker_id = \App\Models\Satker::first()->id ?? 1;
        $petugas_id = \App\Models\User::where('role', 'Petugas SPBP')->first()->id ?? 1;

        // Target penerimaan total
        $targetTerimaPertamax = 30000;
        $targetTerimaDex = 5350;

        // Persediaan awal sesuai permintaan pertama
        $awalPertamax = 2350;
        $awalDex = 1243;

        PembelianBbm::create([
            'tanggal' => '2025-12-28',
            'jenis_bbm' => 'Pertamax',
            'jumlah' => $awalPertamax
        ]);
        PembelianBbm::create([
            'tanggal' => '2025-12-28',
            'jenis_bbm' => 'Pertamina Dex',
            'jumlah' => $awalDex
        ]);

        $currentPertamax = $awalPertamax;
        $currentDex = $awalDex;
        
        $sisaTerimaPertamax = $targetTerimaPertamax;
        $sisaTerimaDex = $targetTerimaDex;

        $currentDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 3, 31);

        while ($currentDate <= $endDate) {
            $isWeekday = $currentDate->isWeekday();

            // 1. Cek apakah butuh penerimaan (jika saldo awal hari ini mendekati 3500 atau butuh diserap)
            // Lakukan penerimaan jika saldo < 4500 dan masih ada sisa target penerimaan
            
            if ($isWeekday) {
                // Penerimaan Pertamax
                if ($currentPertamax <= 4500 && $sisaTerimaPertamax > 0) {
                    $terima = min(8000, $sisaTerimaPertamax);
                    PembelianBbm::create([
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'jenis_bbm' => 'Pertamax',
                        'jumlah' => $terima
                    ]);
                    $currentPertamax += $terima;
                    $sisaTerimaPertamax -= $terima;
                }

                // Penerimaan Dex
                if ($currentDex <= 4000 && $sisaTerimaDex > 0) {
                    $terima = min(8000, $sisaTerimaDex);
                    PembelianBbm::create([
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'jenis_bbm' => 'Pertamina Dex',
                        'jumlah' => $terima
                    ]);
                    $currentDex += $terima;
                    $sisaTerimaDex -= $terima;
                }
                
                // Pastikan sisa penerimaan dihabiskan sebelum akhir Maret (tgl 24) agar konsumsi minggu terakhir bisa disesuaikan
                if ($currentDate->format('Y-m-d') == '2026-03-24') {
                    if ($sisaTerimaPertamax > 0) {
                        PembelianBbm::create([
                            'tanggal' => $currentDate->format('Y-m-d'),
                            'jenis_bbm' => 'Pertamax',
                            'jumlah' => $sisaTerimaPertamax
                        ]);
                        $currentPertamax += $sisaTerimaPertamax;
                        $sisaTerimaPertamax = 0;
                    }
                    if ($sisaTerimaDex > 0) {
                        PembelianBbm::create([
                            'tanggal' => $currentDate->format('Y-m-d'),
                            'jenis_bbm' => 'Pertamina Dex',
                            'jumlah' => $sisaTerimaDex
                        ]);
                        $currentDex += $sisaTerimaDex;
                        $sisaTerimaDex = 0;
                    }
                }

                // 2. Pengeluaran (Transaksi BBM)
                if ($currentDate->format('Y-m-d') >= '2026-03-25') {
                    // Di minggu terakhir Maret, biarkan stok turun di bawah 3500 menuju target spesifik:
                    // Target Pertamax: 2113, Target Dex: 371
                    $daysLeft = $currentDate->diffInDays($endDate) + 1; // 7 down to 1
                    
                    if ($daysLeft == 1) {
                        // Hari terakhir, paskan langsung ke target
                        $konsumsiPertamax = max(0, $currentPertamax - 2113);
                        $konsumsiDex = max(0, $currentDex - 371);
                    } else {
                        // Sebar konsumsi secara merata agar grafik turunnya mulus
                        $konsumsiPertamax = (int)(max(0, $currentPertamax - 2113) / $daysLeft);
                        $konsumsiDex = (int)(max(0, $currentDex - 371) / $daysLeft);
                        
                        // Beri sedikit random agar tidak terlalu statis tiap hari
                        $konsumsiPertamax += rand(-20, 20);
                        $konsumsiDex += rand(-5, 5);
                    }
                } else {
                    // Aturan normal (Januari - pertengahan Maret): Jaga stok agar tidak jatuh di bawah 3500
                    $maxKonsumsiPertamax = max(0, $currentPertamax - 3500);
                    $konsumsiPertamax = min(rand(200, 600), $maxKonsumsiPertamax);
                    
                    $maxKonsumsiDex = max(0, $currentDex - 3500);
                    $konsumsiDex = min(rand(50, 150), $maxKonsumsiDex);
                }
                
                if ($konsumsiPertamax > 0) {
                    TransaksiBbm::create([
                        'satker_id' => $satker_id,
                        'petugas_id' => $petugas_id,
                        'nama_driver' => 'Driver Dummy',
                        'tanggal' => $currentDate->format('Y-m-d H:i:s'),
                        'liter' => $konsumsiPertamax,
                        'harga_per_liter' => 13500,
                        'total' => $konsumsiPertamax * 13500,
                        'jenis_bbm' => 'Pertamax',
                    ]);
                    $currentPertamax -= $konsumsiPertamax;
                }
                
                if ($konsumsiDex > 0) {
                    TransaksiBbm::create([
                        'satker_id' => $satker_id,
                        'petugas_id' => $petugas_id,
                        'nama_driver' => 'Driver Dummy',
                        'tanggal' => $currentDate->format('Y-m-d H:i:s'),
                        'liter' => $konsumsiDex,
                        'harga_per_liter' => 14000,
                        'total' => $konsumsiDex * 14000,
                        'jenis_bbm' => 'Pertamina Dex',
                    ]);
                    $currentDex -= $konsumsiDex;
                }
            }

            $currentDate->addDay();
        }
    }
}
