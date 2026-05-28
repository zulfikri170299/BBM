<?php

require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = 'E:/LAPHAR.xlsx';

$reader = IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

echo json_encode(array_slice($sheetData, 0, 10), JSON_PRETTY_PRINT);
