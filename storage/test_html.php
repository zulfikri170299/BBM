<?php
try {
    $pw = \PhpOffice\PhpWord\IOFactory::load('E:\\BA.docx');
    $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'HTML');
    $htmlFile = sys_get_temp_dir() . '/ba_dump.html';
    $htmlWriter->save($htmlFile);
    copy($htmlFile, public_path('ba_dump.html'));
    dump('Saved to public/ba_dump.html');
} catch(\Exception $e) {
    dump($e->getMessage());
}
