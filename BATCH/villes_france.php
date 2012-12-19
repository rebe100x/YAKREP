<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta http-equiv="content-type"
	content="text/html;charset=utf-8" />

<?php
/* batch to parse villes de france */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/francevilles.csv";
$origin = "Wikipedia Yakwala";
$fileTitle = "Villes de France";
$licence = "Yakwala";
$country = 'France';
$status = 1;
$debug = 1;
$cat = array("GEOLOCALISATION#VILLE", "GEOLOCALISATION");
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

$lastid =  '';
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

  while (($data = fgetcsv($handle, 50000, ",")) !== FALSE) {
    if($row  > 0 ){
      foreach ($data as $key => &$value) {
        $value = trim($value);
      }
      if (trim($data[1]) == $lastid) continue;
      else {$lastid = trim($data[1]);}
      //sleep(1);
      $currentPlace = new Place();
      $currentPlace->title = $data[3];
      $currentPlace->origin = $origin;
      $currentPlace->filesourceTitle = $fileTitle;
      $currentPlace->licence = $licence;
      $currentPlace->filesourceId = '50c883f49bab882610000000';
      $currentPlace-> status = $status;
      $currentPlace->setYakCat($cat);
      	
      $zn = substr(trim($data[0]),0,2);
      if (trim($data[8]) == "BRETAGNE")
      $zone = 15;
      elseif ($zn == "77")
      $zone = 7;
      elseif ($zn == "78")
      $zone = 8;
      elseif ($zn == "91")
      $zone = 9;
      elseif ($zn == "92")
      $zone = 11;//10
      elseif ($zn == "93")
      $zone = 12;//11
      elseif ($zn == "94")
      $zone = 10;//12
      elseif ($zn == "95")
      $zone = 13;
      elseif ($zn == "34")
      $zone = 2;
      	
      else{
        $results['rejected'] ++;
        $results['row'] ++;
        $row++;
        continue;
      }
      $currentPlace->zone = $zone;
      	
      unset($titles);
      $title1 ='';$title2 ='';$title3 ='';$title4 ='';
      $title = $currentPlace->title;
      $titles[] = $title;
      	
      $lng =''; $lat = '';
      if ($data[11] <> '' ) $lng = trim($data[11]);
      if ($data[12] <> '' ) $lat = trim($data[12]);

      //tests on title :

      if (strpos($title, '-') !== FALSE) {
        $title1= str_replace('-',' ',$title);
        $titles[] = $title1;
        if (strpos($title1,'Saint')!== FALSE) {
          $title2= str_replace('Saint','St',$title1);
          $titles[] = $title2;
          //echo  strpos($title,'Saint');
        }
      }

      if (strpos($title,'Saint')!== FALSE  ) {
        $title3= str_replace('Saint','St',$title);
        $titles[] = $title3;
        if (strpos($title3,'-')!== FALSE) {
          $title4= str_replace('-',' ',$title3);
          $titles[] = $title4;
        }
      }
      $titles = array_unique($titles);
      if (trim($data[2]) == "L'")  {
        for ($i = 0; $i<sizeof($titles); $i++) {
          $titles[$i] = "L'".$titles[$i];
        }

      }
      $address['city'] = trim($data[3]);
      $address['country'] = $country;
      $address['zip'] = trim($data[0]);
      $address['state'] = trim($data[10]);
      $address['area'] = trim($data[8]);
      $currentPlace->address = $address;
      $currentPlace->formatted_address =  $address['city'] .", ".	$address['state'].", $country" ;
      if (($lng<>'') &&($lat <>'')) {$locationQuery = '';
      $currentPlace-> location =new location($lat,$lng);
      }
      else $locationQuery= $data[3]. ' ' . $data[10].', France';

      for ($i = 0; $i < sizeof($titles); $i++){
        $currentPlace->title = $titles[$i];
        //print_r($currentPlace);

        $res = $currentPlace->saveToMongoDB($locationQuery, $debug,$updateFlag);
        //print_r($res);

      }
    }

    $results['row'] ++;
    $row++;

  }

  fclose($handle);


  echo "REJECTED RESULTS: ".$results['rejected'];
  echo "OVERALL RESULTS: ".$results['row'];


  // $currentPlace->prettyLog($results);

}

