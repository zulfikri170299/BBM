<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ref = new ReflectionMethod('App\Http\Controllers\Admin\KendaraanController', 'laporanBulanan');
echo $ref->getFileName() . ' : ' . $ref->getStartLine();
