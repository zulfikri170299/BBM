<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = new \App\Http\Controllers\Admin\LaporanTriwulanController;
$req = new \Illuminate\Http\Request();
$req->merge(['tahun'=>2026,'triwulan'=>1]);
$data = $t->index($req)->getData();
echo "PENDAPATAN:\n";
print_r($data['pendapatan']);
echo "\nPEMAKAIAN:\n";
print_r($data['pemakaian']);
echo "\nSISA BBM:\n";
print_r($data['sisaBbm']);
