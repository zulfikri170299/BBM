<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logs = \App\Models\LogAktivitas::where('aktivitas', 'like', '%Import%')->orderBy('id', 'desc')->take(10)->get();
foreach($logs as $log) {
    echo "- [{$log->created_at}] {$log->aktivitas}\n";
}

$riwayats = \App\Models\RiwayatTopup::where('metode', 'IMPORT')->orderBy('id', 'desc')->take(5)->get();
foreach($riwayats as $r) {
    echo "- [{$r->created_at}] Satker: {$r->satker_id}, Kend: {$r->kendaraan_id}, Jml: {$r->jumlah}\n";
}
