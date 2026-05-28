<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = 'D:\PROJEK\LAPORAN BBM TAHUNAN.xlsx';

try {
    $spreadsheet = IOFactory::load($inputFileName);
    $data = [];
    foreach ($spreadsheet->getSheetNames() as $sheetName) {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();
        
        $sheetData = $sheet->rangeToArray(
            'A1:' . $highestColumn . min(10, $highestRow),
            NULL,
            TRUE,
            FALSE
        );
        
        $data[$sheetName] = $sheetData;
    }
    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    echo 'Error loading file: ', $e->getMessage();
}
