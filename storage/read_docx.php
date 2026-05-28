<?php
$z = new ZipArchive; 
if ($z->open(public_path('word_media/BA.docx')) === TRUE) { 
    $xml = $z->getFromName('word/document.xml'); 
    echo "DECODED CONTENT FROM public/word_media/BA.docx:\n\n";
    $xml = str_replace('<w:p', "\n<w:p", $xml);
    $text = strip_tags($xml); 
    dump(substr($text, 0, 5000)); 
} else { 
    dump('Failed to open zip'); 
}
