<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gCodes = ['G4 1', 'G7 1', 'G9 1', 'G9 2', 'G9 3', 'G10 1', 'G21 1', 'G21 2', 'G21 3', 'G21 4', 'G21 5', 'G21 6', 'G21 7', 'G130 1', 'G130 2'];

foreach ($gCodes as $code) {
    echo "=== KENDARAAN: {$code} ===\n";
    $k = \App\Models\Kendaraan::where('no_polisi', $code)->first();
    if ($k) {
        echo "Status di DB: ADA (ID: {$k->id}, Satker: {$k->satker_id}, BBM: {$k->jenis_bbm}, Saldo: {$k->saldo})\n";
    } else {
        echo "Status di DB: TERHAPUS / TIDAK ADA\n";
    }
    
    // Cari log aktivitas
    $logs = \App\Models\LogAktivitas::where('aktivitas', 'like', "%{$code}%")->get();
    foreach ($logs as $l) {
        echo "   [{$l->created_at}] User {$l->user_id}: {$l->aktivitas}\n";
    }
    echo "\n";
}

