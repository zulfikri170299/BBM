<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transactions = \App\Models\TransaksiBbm::orderBy('id', 'desc')->take(5)->get();
foreach($transactions as $trx) {
    echo "ID: {$trx->id} | Tanggal: {$trx->tanggal} | Created: {$trx->created_at}\n";
}
