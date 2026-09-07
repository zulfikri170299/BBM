<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rendis = \App\Models\RendisBbm::find(1);
if (!$rendis) { echo "Rendis 1 not found."; exit; }
$ids = $rendis->rendisKendaraans->pluck('kendaraan.satker_id')->unique()->values()->toArray();
$names = \App\Models\Satker::whereIn('id', $ids)->pluck('nama_satker')->toArray();
echo json_encode($names);
