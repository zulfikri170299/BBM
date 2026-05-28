<?php
require 'vendor/autoload.php';
$pth = 'D:\PROJEK\SINKRONISASI BBM.xlsx';
if(file_exists($pth)){
    $spr = \PhpOffice\PhpSpreadsheet\IOFactory::load($pth);
    $act = $spr->getActiveSheet();
    foreach($act->getRowIterator() as $row){
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE);
        $rData = [];
        foreach ($cellIterator as $cell) {
            $rData[] = $cell->getFormattedValue();
        }
        echo implode(' | ', $rData) . "\n";
    }
} else {
    echo 'File not found';
}
