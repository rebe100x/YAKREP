<?php
/* Bacth for Yakwala proto horaire :
 * update X and Y with GMAP () original file precision was not enough
 * 
 * */

ini_set('display_errors',1);
$filenameInput = "./input/horaire_full.csv";
$filenameOutput = "./output/horaire_full.csv";
$row = 0;
$centressante = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        
        //for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
        //  echo $row;
        //    $centressante[$row] .= $data[$c].";";
        //}
        
        
        if($row == 0){
                $csvHead =  $data;
            }else{
            $centressante[$row-1] = $data;
            }
        
            
        $i++;
        $row++;
    }
    fclose($handle);
}


$fp = fopen($filenameOutput, 'wt');

// TRAITMENT
$join_id = 0;


//SAUVEGARDE
$i=0;
$j=0;
$csvHead2 = '';
$address = '';
$addressOld = '';
$lat = 0;
$lng = 0;
$latOld = 0;
$lngOld = 0;

//var_dump($csvHead);
for($k=0;$k<sizeof($csvHead);$k++){
	
        $csvHead2 .= "\"".$csvHead[$k]."\";";
}

$csvHead2 .= "\"LAT\";";
$csvHead2 .= "\"LNG\";";

fwrite($fp, $csvHead2."\n");
$fields = array();

foreach($centressante as $fields) {

    for($j=0;$j<sizeof($fields)-1;$j++){
        
        if(empty($fields[$j]) || $fields[$j] == "")
            $fields[$j] = "null";
        
        $fieldsProcessed[$i] .= "\"".stripslashes($fields[$j])."\";";
        
        
    }
    
    $addressOld = $address;
    $address = $fields[8].', '.$fields[9].', '.$fields[10];
    
    if(empty($addressOld) || $addressOld != $address ){
	    $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
		echo $url;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result);
		//var_dump($json->results[0]->geometry->location);
        
		$lat = $json->results[0]->geometry->location->lat;
        $lng = $json->results[0]->geometry->location->lng;
        $latOld = $lat;
        $lngOld = $lng;
    }else{
    	echo 'OLD<br>';
        $lat = $latOld;
        $lng = $lngOld;
    }
        
    
    $fieldsProcessed[$i] .= "\"".$lat."\";";
    $fieldsProcessed[$i] .= "\"".$lng."\";";
    
    $fieldsProcessed[$i] .= "\n";
    
    fwrite($fp, $fieldsProcessed[$i]);
    $i++;   
    
    
}

fclose($fp);














