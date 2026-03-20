<?php
try {
    $t = new \PhpOffice\PhpWord\TemplateProcessor('E:\\BA.docx');
    $f = tempnam(sys_get_temp_dir(), 'ba').'.docx';
    $t->saveAs($f);
    \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
    \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
    $pw = \PhpOffice\PhpWord\IOFactory::load($f);
    $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'PDF');
    $pdfFile = tempnam(sys_get_temp_dir(), 'bapdf').'.pdf';
    $pdfWriter->save($pdfFile);
    dump('Success: '.$pdfFile);
} catch(\Exception $e) {
    dump($e->getMessage());
}
