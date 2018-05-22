<?php
/**
 * Author @ Dabao Huang
 * Date   @ 2018/05/21
 */

ini_set('default_charset', 'utf-8');
ini_set('mbstring.internal_encoding','UTF-8');

include('../PHP.note/common.php');
if( !in_array($argv[1], Array('monster','tower','item')) ) die('Error parameters.');
include($_SERVER['HTTP_HOST'] . "/Library/C{$argv[1]}.php");
$parser = new "C{$argv[1]}";

var_dump($parser);

// $content = preg_replace('/[\s|\n]/isU','',getCache('https://ro.fws.tw/db/endless/tower/all'));

// preg_match_all('/<div[^>]*>(.*)<\/div>/isU',$content,$matches);

// var_dump($matches);

// file_put_contents('log.html',$content);