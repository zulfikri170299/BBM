<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- PEMERIKSAAN TRANSAKSI BBM PERTAMAX ---\n";
$trxs = \App\Models\TransaksiBbm::where('jenis_bbm', 'like', 'Pertamax%')->get();
foreach ($trxs as $t) {
    echo "ID: {$t->id}, Liter: {$t->liter}, Driver: {$t->nama_driver}, Tanggal: {$t->tanggal}, Satker: {$t->satker_id}, Kendaraan: {$t->kendaraan_id}, Personel: {$t->personel_id}\n";
}

echo "\n--- PEMERIKSAAN RIWAYAT TOPUP (KELUAR/POTONG SALDO) ---\n";
$tops = \App\Models\RiwayatTopup::where('tipe', 'keluar')->get();
foreach ($tops as $o) {
    echo "ID: {$o->id}, Liter: {$o->jumlah}, Jenis: {$o->jenis_bbm}, Ket: {$o->keterangan}, Tanggal: {$o->created_at}\n";
}

echo "\n--- PEMERIKSAAN PERTAMINA DEX ---\n";
$trxs_dex = \App\Models\TransaksiBbm::where('jenis_bbm', 'like', 'Pertamina Dex%')->get();
foreach ($trxs_dex as $t) {
    echo "ID: {$t->id}, Liter: {$t->liter}, Driver: {$t->nama_driver}, Tanggal: {$t->tanggal}\n";
}
