<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;

// Memperbaiki tanggal di TransaksiBbm yang disimpan dalam UTC sebelum perubahan timezone aplikasi
$transactions = \App\Models\TransaksiBbm::where('created_at', '<', '2026-03-04 11:15:00')->get();

$count = 0;
foreach($transactions as $trx) {
    // Tanggal saat ini adalah UTC, kita tambahkan 8 jam agar setara WITA di database
    // Karena aplikasi sekarang mengasumsikan string di database adalah Makasar (WITA)
    $witaTime = Carbon::createFromFormat('Y-m-d H:i:s', $trx->tanggal)->addHours(8)->format('Y-m-d H:i:s');
    $trx->tanggal = $witaTime;
    $trx->save();
    $count++;
}

echo "Berhasil memperbaiki $count data transaksi BBM lama ke zona waktu WITA di database.\n";
