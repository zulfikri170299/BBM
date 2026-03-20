<?php
$z = new ZipArchive;
if ($z->open('E:\\BA.docx') === TRUE) {
    for($i = 0; $i < $z->numFiles; $i++) {
        $stat = $z->statIndex($i);
        if (strpos(strtolower($stat['name']), 'media') !== false || strpos(strtolower($stat['name']), 'image') !== false) {
            dump($stat['name']);
            if (!is_dir(public_path('word_media'))) {
                mkdir(public_path('word_media'), 0777, true);
            }
            $contents = $z->getFromIndex($i);
            $filename = basename($stat['name']);
            file_put_contents(public_path('word_media/'.$filename), $contents);
        }
    }
    $z->close();
    dump('Done checking zip');
} else {
    dump('Failed to open zip');
}
