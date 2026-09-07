<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$satkers = \App\Models\Satker::getOrderedForRendis()->pluck('nama_satker')->toArray();
echo json_encode($satkers, JSON_PRETTY_PRINT);