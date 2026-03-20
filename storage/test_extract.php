<?php
$z = new ZipArchive;
if ($z->open('E:\\BA.docx') === TRUE) {
    if (!is_dir(public_path('word_media'))) {
        mkdir(public_path('word_media'), 0777, true);
    }
    for($i = 0; $i < $z->numFiles; $i++) {
        $stat = $z->statIndex($i);
        if (strpos($stat['name'], 'word/media/') === 0 && $stat['size'] > 0) {
            $contents = $z->getFromIndex($i);
            $filename = basename($stat['name']);
            file_put_contents(public_path('word_media/'.$filename), $contents);
        }
    }
    $z->close();
    dump('Extracted media to public/word_media');
} else {
    dump('Failed to open zip');
}
