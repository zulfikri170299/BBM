<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$import = new \App\Imports\TopupSaldoImport();
echo "Before Import: " . json_encode($import->satkerSummary) . "\n";

// Bikin dummy file buat import
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A2', 'NOPOL');
$sheet->setCellValue('B2', 'KODE');
$sheet->setCellValue('C2', 'JUMLAH');
$sheet->setCellValue('A3', 'TEST NOPOL');
$sheet->setCellValue('B3', 'TEST KODE');
$sheet->setCellValue('C3', 10);

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

try {
    \Maatwebsite\Excel\Facades\Excel::import($import, $uploadedFile);
} catch (\Exception $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
}

echo "After Import: " . json_encode($import->satkerSummary) . "\n";
echo "Errors: " . json_encode($import->errors) . "\n";
