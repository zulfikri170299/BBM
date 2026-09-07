<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ids = \App\Models\Kendaraan::pluck('satker_id')->unique()->values()->toArray();
$names = \App\Models\Satker::whereIn('id', $ids)->pluck('nama_satker')->toArray();
echo json_encode($names);
