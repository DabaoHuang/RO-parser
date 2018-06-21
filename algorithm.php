<?php

$test = json_decode(base64_decode(unserialize(file_get_contents('tower/floorinfo.txt'))),true);

$search_array = array('犬妖首領');
$final = false;

// 線路
foreach ($test as $rows) {

    $priority = 0;

    // 樓層
    for( $i=3 ; $i <= 90 ; $i+=3 ) {

        if( substr($i,-1,1) == 9 ) ++$i;

        for( $j=0 ; $j < count($rows['Tower'][$i]) ; $j++ ) {

            if( in_array($rows['Tower'][$i][$j]['Cname'],$search_array) ) {

                if( !$final ) $final = array();
                if( !isset($final[$rows['transit']]) ) $final[$rows['transit']] = $rows['Tower'];
                $final[$rows['transit']]['priority'] = ++$priority;

            }
        }

    }

}
if(!$final) die('沒有符合條件的資料');
print_r($final);


?>