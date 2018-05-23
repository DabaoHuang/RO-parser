<?php 

/**
 * Author @ Dabao Huang
 * Date   @ 2018/05/23
 */

Class Ctower extends Ccommon {

    var $content;
    var $baseurl = 'https://ro.fws.tw';
    var $url = 'https://ro.fws.tw/db/endless/tower/all'; // Tower info base
    var $TowerInfo = array(); // Final data output
    var $TLv = array(); // MVP, mini floors
    var $Mimg = array(); // MVP, mini image
    var $MaxFloors = 90; // Max of tower's floors.
    var $cachetime = 86400; // Max of tower's floors.

    function __construct()
    {
        echo "\nClass Ctower started. \n";
        $this->content = $this->getCache($this->url,0);
        echo "Get page from {$this->url} \n";
        // check monster data
        $this->CheckMonster();
        $this->TowerLevel();
        $this->ParserStart();
    }

    private function ParserStart()
    {
        preg_match_all('/<div[^>]*class=\"[^\"]*level_monsters[^\"]*\"[^>]*>(.*)<\/div>/isU',$this->content,$matches);

        foreach ($matches[1] as $row) {
            preg_match_all('/\/db\/endless\/report\/(.*)\/error/isU',$row,$info);

            if( !isset($info) ) continue;
            $info = explode('/',$info[1][0]); // 0 : transit ; 1 : floor
            
            if( !isset($this->TowerInfo[$info[0]]) ) {
                $this->TowerInfo[$info[0]] = Array(
                    'transit' => $info[0],
                    'Tower' => $this->TLv
                );
            }

            preg_match_all('/<a[^>]*class=\"monster_mini[^\"]*\"[^>]*background-image: url\(\/(.*)\.(png|jpg)\)\;/isU',$row,$Minfo);

            foreach ($Minfo[1] as $Mrow){
                $Mrow = explode('/',$Mrow);
                $Mrow = $Mrow[(count($Mrow)-1)];
               $this->TowerInfo[$info[0]]['Tower'][$info[1]][] = $this->Mimg[$Mrow];
            }
        }
        echo "Tower monster update finish.\n";
        file_put_contents("tower/floorinfo.txt",serialize(base64_encode(json_encode($this->TowerInfo))));
    }

    private function TowerLevel()
    {
        for( $i=3 ; $i <= $this->MaxFloors ; $i+=3 ) {
            if ( substr($i,-1,1) == 9 ) ++$i;
            $this->TLv[$i] = Array();
        }
    }

    private function CheckMonster()
    {
        echo "Checking BOSS image data. \n";
        $cachepath = 'tower/Bossimage.txt';
        
        if( file_exists($cachepath) && (strtotime('now') - filectime($cachepath)) < $this->cachetime) {
            echo "Has Boss image data. \n";
            $this->Mimg = json_decode(file_get_contents($cachepath),true);
        } else {
            echo "Update Boss image data. \n";
            // update Bossimage
            preg_match_all('/<a[^>]*class=\"monster_mini\"[^>]*>(.*)<\/a>/isU',$this->content, $matches);

            for( $i=0 ; $i < count($matches[0]) ; $i++ ) {
                // get image
                preg_match_all('/background-image: url\((.*)\)\;/isU',$matches[0][$i],$matches2);
                // get monster name
                $Mname = preg_replace('/(\/images\/unpack\/gui\/face\/|\.png)/isU','',$matches2[1][0]);
                $MCname = preg_replace('/<span class=\"after\">DENY<\/span>/isU','',$matches[1][$i]);
                $this->Mimg[$Mname]['img'] = $this->baseurl . $matches2[1][0];
                $this->Mimg[$Mname]['Cname'] = $MCname;
            }
            $this->Mimg['unknow']['img'] = $this->baseurl . '/images/unknow.png';
            $this->Mimg['unknow']['Cname'] = '未知';
            file_put_contents($cachepath,json_encode($this->Mimg));
        }

        if( !$this->Mimg ) die('does not have BOSS image data.');
    }
}