<?php
$file = 'd:/PROJEK/BBM/BBM/resources/views/admin/rendis/excel.blade.php';
$content = file_get_contents($file);
$content = preg_replace('/number_format\((.*?),\s*0,\s*\'\,\',\s*\'\.\'\)/', 'round($1)', $content);
file_put_contents($file, $content);
echo "Done";
