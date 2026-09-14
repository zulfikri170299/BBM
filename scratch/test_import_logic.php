<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = json_decode(file_get_contents(__DIR__.'/rows_dump.json'), true);

$triwulanRowIndex = -1;
$pembelianRowIndex = -1;
$hariB1RowIndex = -1;

for ($i = 0; $i < 10; $i++) {
    $colA = strtolower(trim($rows[$i][0] ?? ''));
    if ($colA === 'triwulan') {
        $triwulanRowIndex = $i;
    } elseif ($colA === 'pembelian pertamax') {
        $pembelianRowIndex = $i;
    } elseif (strpos($colA, '=if(') === 0 || in_array($colA, ['januari', 'april', 'juli', 'oktober', 'bulan 1'])) {
        if ($hariB1RowIndex === -1) $hariB1RowIndex = $i;
    }
}

echo "Triwulan Index: $triwulanRowIndex\n";
echo "Pembelian Index: $pembelianRowIndex\n";
echo "Hari B1 Index: $hariB1RowIndex\n";
