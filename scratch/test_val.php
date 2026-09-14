<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
$s = new Spreadsheet();
$sheet = $s->getActiveSheet();
$val = new DataValidation();
$val->setType(DataValidation::TYPE_CUSTOM);
$val->setFormula1('FALSE');
$sheet->setDataValidation('D1:F10', clone $val);
$sheet->setCellValue('D1', 'test');
$sheet->setCellValue('E1', 'test');
$sheet->setCellValue('F1', 'test');
$w = new Xlsx($s);
$w->save('scratch/test_val.xlsx');
echo 'Saved';
