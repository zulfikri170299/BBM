<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'super_admin')->first();
auth()->login($user);

// Beri saldo admin yang cukup
\App\Models\AdminBbmStock::updateOrCreate(
    ['jenis_bbm' => 'Pertamax'],
    ['saldo' => 1000]
);
\App\Models\AdminBbmStock::updateOrCreate(
    ['jenis_bbm' => 'Pertamina Dex'],
    ['saldo' => 1000]
);

$k = \App\Models\Kendaraan::where('jenis_bbm', 'Pertamax')->first();
if (!$k) {
    die("No pertamax vehicle");
}

echo "Testing with NOPOL: " . $k->no_polisi . "\n";

$import = new \App\Imports\TopupSaldoImport();

// Bikin dummy file buat import
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A2', 'NOPOL');
$sheet->setCellValue('B2', 'KODE');
$sheet->setCellValue('C2', 'JUMLAH');
$sheet->setCellValue('A3', $k->no_polisi);
$sheet->setCellValue('B3', $k->kode_kendaraan);
$sheet->setCellValue('C3', 6);

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$tempFile = tempnam(sys_get_temp_dir(), 'test_import');
$writer->save($tempFile);

$uploadedFile = new \Illuminate\Http\UploadedFile(
    $tempFile,
    'test_import.xlsx',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    null,
    true
);

\Maatwebsite\Excel\Facades\Excel::import($import, $uploadedFile);

// Lakukan hal yang sama seperti di controller
$baController = new \App\Http\Controllers\Admin\BaController();
foreach ($import->satkerSummary as $satkerId => $totals) {
    echo "Triggering BA for Satker $satkerId ...\n";
    $satker = \App\Models\Satker::find($satkerId);
    if ($satker) {
        $result = $baController->automatedGenerate($satker, $totals, now()->month, now()->year);
        echo "BA Result: " . ($result ? 'Success' : 'Failed') . "\n";
    }
}

echo "Done. Total Success: {$import->successCount}\n";
