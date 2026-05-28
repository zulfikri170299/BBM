<?php
$z = new ZipArchive;
if ($z->open('E:\\BA.docx') === TRUE) {
    for($i = 0; $i < $z->numFiles; $i++) {
        $stat = $z->statIndex($i);
        dump($stat['name']);
    }
    dump('Done checking zip');
} else {
    dump('Failed to open zip');
}
