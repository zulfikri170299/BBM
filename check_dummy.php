<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$awalPertamax = 2350;
$awalDex = 1243;

$terimaPertamax = App\Models\PembelianBbm::where('jenis_bbm', 'Pertamax')->where('tanggal', '>=', '2026-01-01')->sum('jumlah');
$terimaDex = App\Models\PembelianBbm::where('jenis_bbm', 'Pertamina Dex')->where('tanggal', '>=', '2026-01-01')->sum('jumlah');

$keluarPertamax = App\Models\TransaksiBbm::where('jenis_bbm', 'Pertamax')->where('tanggal', '>=', '2026-01-01')->sum('liter');
$keluarDex = App\Models\TransaksiBbm::where('jenis_bbm', 'Pertamina Dex')->where('tanggal', '>=', '2026-01-01')->sum('liter');

echo "Pertamax:\n";
echo "Awal: " . $awalPertamax . "\n";
echo "Terima: " . $terimaPertamax . "\n";
echo "Keluar: " . $keluarPertamax . "\n";
echo "Akhir: " . ($awalPertamax + $terimaPertamax - $keluarPertamax) . "\n\n";

echo "Dex:\n";
echo "Awal: " . $awalDex . "\n";
echo "Terima: " . $terimaDex . "\n";
echo "Keluar: " . $keluarDex . "\n";
echo "Akhir: " . ($awalDex + $terimaDex - $keluarDex) . "\n";
