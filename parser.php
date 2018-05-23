<?php
/**
 * Author @ Dabao Huang
 * Date   @ 2018/05/21
 */

ini_set('default_charset', 'utf-8');
ini_set('mbstring.internal_encoding','UTF-8');
ini_set('display_errors',0);

require('../PHP.note/common.php');

$folder = Array('monster','tower','item');

if( !in_array($argv[1], $folder) ) die('Error parameters.');

foreach ($folder as $foldername) {
    if( !file_exists($foldername) ) mkdir($foldername);
}

require("Library/C{$argv[1]}.php");
$classname = "C{$argv[1]}";
$parser = new $classname;

echo "Done.\n";

// file_put_contents('log.html',$content);