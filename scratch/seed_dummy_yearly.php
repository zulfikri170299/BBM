<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$userId = 1; // Super Admin
$satkerId = DB::table('satkers')->first()->id ?? 1;
$kendaraanId = DB::table('kendaraans')->first()->id ?? 1;

$years = [2023, 2024, 2025];

foreach ($years as $year) {
    echo "=== Membuat data dummy untuk tahun {$year} ===\n";

    // 1. Transaksi BBM (3 record)
    for ($m = 1; $m <= 3; $m++) {
        DB::table('transaksi_bbms')->insert([
            'kendaraan_id' => $kendaraanId,
            'satker_id' => $satkerId,
            'tanggal' => Carbon::create($year, $m, 15)->format('Y-m-d'),
            'liter' => rand(10, 50),
            'harga_per_liter' => 13000,
            'total' => 13000 * rand(10, 50),
            'jenis_bbm' => 'Pertamax',
            'nama_driver' => 'Driver Dummy ' . $year,
            'created_at' => Carbon::create($year, $m, 15, 10, 0, 0)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 15, 10, 0, 0)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 3 Transaksi BBM\n";

    // 2. Riwayat Topup (3 record)
    for ($m = 1; $m <= 3; $m++) {
        DB::table('riwayat_topups')->insert([
            'kendaraan_id' => $kendaraanId,
            'satker_id' => $satkerId,
            'user_id' => $userId,
            'jumlah' => rand(20, 100),
            'tipe' => 'masuk',
            'metode' => 'manual',
            'jenis_bbm' => 'Pertamax',
            'status' => 'success',
            'keterangan' => 'Data dummy ' . $year,
            'created_at' => Carbon::create($year, $m, 10, 8, 0, 0)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 10, 8, 0, 0)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 3 Riwayat Topup\n";

    // 3. Riwayat Stok Admin (2 record)
    for ($m = 1; $m <= 2; $m++) {
        DB::table('riwayat_stok_admins')->insert([
            'user_id' => $userId,
            'jenis_bbm' => 'Pertamax',
            'jumlah' => rand(50, 200),
            'tipe' => 'masuk',
            'keterangan' => 'Dummy stok masuk ' . $year,
            'created_at' => Carbon::create($year, $m, 5, 9, 0, 0)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 5, 9, 0, 0)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 2 Riwayat Stok Admin\n";

    // 4. BA Logs (1 record)
    DB::table('ba_logs')->insert([
        'satker_id' => $satkerId,
        'bulan' => 6,
        'tahun' => $year,
        'total_pertamax' => rand(100, 500),
        'total_dex' => rand(50, 200),
        'file_path' => '',
        'created_at' => Carbon::create($year, 6, 15)->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::create($year, 6, 15)->format('Y-m-d H:i:s'),
    ]);
    echo "  - 1 Berita Acara\n";

    // 5. Log Aktivitas (2 record)
    for ($m = 1; $m <= 2; $m++) {
        DB::table('log_aktivitas')->insert([
            'user_id' => $userId,
            'aktivitas' => "Aktivitas dummy {$year} bulan {$m}",
            'created_at' => Carbon::create($year, $m, 20, 14, 0, 0)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 20, 14, 0, 0)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 2 Log Aktivitas\n";

    // 6. Satisfaction Index (1 record)
    DB::table('satisfaction_indices')->insert([
        'user_id' => $userId,
        'rating' => strval(rand(1, 3)),
        'note' => 'Feedback dummy ' . $year,
        'created_at' => Carbon::create($year, 7, 1)->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::create($year, 7, 1)->format('Y-m-d H:i:s'),
    ]);
    echo "  - 1 Indeks Kepuasan\n";

    // 7. Catatan (2 record)
    for ($m = 1; $m <= 2; $m++) {
        DB::table('catatans')->insert([
            'user_id' => $userId,
            'judul' => "Catatan Dummy {$year} #{$m}",
            'isi' => "Ini adalah catatan percobaan untuk tahun {$year}.",
            'warna' => '#3B82F6',
            'created_at' => Carbon::create($year, $m, 25)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 25)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 2 Catatan\n";

    // 8. Notifikasi (2 record)
    for ($m = 1; $m <= 2; $m++) {
        DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'App\Notifications\TopupNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => json_encode(['title' => 'Notifikasi Dummy', 'message' => "Notifikasi dummy {$year}"]),
            'created_at' => Carbon::create($year, $m, 12)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::create($year, $m, 12)->format('Y-m-d H:i:s'),
        ]);
    }
    echo "  - 2 Notifikasi\n";

    echo "Selesai untuk tahun {$year}!\n\n";
}

echo "=== DONE: Data dummy berhasil dibuat untuk tahun 2023, 2024, dan 2025 ===\n";
