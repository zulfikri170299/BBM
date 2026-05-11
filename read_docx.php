<?php
$zip = new ZipArchive;
$file = 'D:\PROJEK\BBM\public\word_media\LAPORAN BBM RUTIN  TW I 2026.docx';
if ($zip->open($file) === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    
    // Quick parse to get text
    $doc = new DOMDocument();
    $doc->loadXML($xml);
    $tables = $doc->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'tbl');
    
    foreach ($tables as $index => $table) {
        echo "Table " . ($index + 1) . ":\n";
        $rows = $table->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'tr');
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'tc');
            $rowText = [];
            foreach ($cells as $cell) {
                $paras = $cell->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'p');
                $cellText = '';
                foreach ($paras as $p) {
                    $texts = $p->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 't');
                    foreach ($texts as $t) {
                        $cellText .= $t->nodeValue;
                    }
                    $cellText .= " ";
                }
                $rowText[] = trim($cellText);
            }
            echo implode(" | ", $rowText) . "\n";
        }
        echo "---------------------------------\n";
    }
} else {
    echo "Failed to open docx";
}
