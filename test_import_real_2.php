<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'super_admin')->first();
auth()->login($user);

$k = \App\Models\Kendaraan::first();
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
$sheet->setCellValue('C3', 1);

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

echo "After Import Summary: " . json_encode($import->satkerSummary) . "\n";
echo "Success Count: " . $import->successCount . "\n";
echo "Errors: " . json_encode($import->errors) . "\n";
