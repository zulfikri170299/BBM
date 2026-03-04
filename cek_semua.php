<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Log Hari Ini:\n";
$logs = \App\Models\LogAktivitas::where('created_at', '>=', \Carbon\Carbon::today()->toDateString())->orderBy('id', 'asc')->get();
foreach($logs as $log) {
    echo "- [{$log->created_at}] {$log->aktivitas}\n";
}
