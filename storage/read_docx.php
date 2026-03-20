<?php
$z = new ZipArchive; 
if ($z->open('E:\\BA.docx') === TRUE) { 
    $xml = $z->getFromName('word/document.xml'); 
    $z->close(); 
    $xml = str_replace('<w:p', "\n<w:p", $xml);
    $text = strip_tags($xml); 
    dump(substr($text, 0, 5000)); 
} else { 
    dump('Failed to open zip'); 
}
