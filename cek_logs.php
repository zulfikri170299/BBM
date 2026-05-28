<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Log Aktivitas Terbaru:\n";
$logs = \App\Models\LogAktivitas::latest()->take(10)->get();
foreach($logs as $log) {
    echo "- [{$log->created_at}] {$log->aktivitas}\n";
}

echo "\nBA Logs Terbaru:\n";
$baLogs = \App\Models\BaLog::latest()->take(5)->get();
foreach($baLogs as $ba) {
    echo "- [{$ba->created_at}] Satker ID: {$ba->satker_id}, P: {$ba->total_pertamax}, D: {$ba->total_dex}, File: {$ba->file_path}\n";
}
