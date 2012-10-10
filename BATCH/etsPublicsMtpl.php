<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Yakwala Batch</title>
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />
</head>

<body>
<?php
/* batch to parse "Etablissements publics" MONTPELLIER ZONE 2
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EtabliPublic_2011.csv";
$origin = "http://opendata.montpelliernumerique.fr/Etablissements-publics";
$fileTitle = "Etablissements publics";
$licence = "licence ouverte";
$debug = 0;
$row = 0;
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0);
$updateFlag = empty($_GET['updateFlag'])?0:1;
if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {
        $num = count($data);
        
		if($row > 0){
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$place = new Place;

			if(!empty($data[13])){
				$title =  $data[13];
				if($data[13] != $data[5])
					$content =  $data[5]."<br>";
				else
					$content = "";
			}elseif(!empty($data[5])){
				$title =  $data[5];
				$content = "";
			}
			
			$place->title = $title;
			$place->content = $content;
			$place->origin = $origin;
			$place->filesourceTitle = $fileTitle;
			$place->licence = $licence;
			$place->setTel($data[16], "tel");
			$place->setMail($data[18]);
			$place->setWeb($data[19]);
			$place->setZone("MONTPELLIER");
			
			// YakCat
			$cat = array("GEOLOCALISATION","GEOLOCALISATION#YAKDICO");
			$place->setYakCat($cat);
			$place->setYakCat(array($data[9]));

			if( !empty($data[33]) || !empty($data[34]) || !empty($data[20]) )
				$place->setTagCarPark();
			
			$transportation = "";
			if( !empty($data[37]) )
				$transportation = "Tram: ". $data[37]."<br>";
			if( !empty($data[38]) )
				$transportation = "Bus: ". $data[37];		
				
			$place->contact->transportation = $transportation;
				
			
			$locationQuery = $data[10] . ', ' . $data[11] . ' ' . $data[12] . ', France';
		
			
			$res = $place->saveToMongoDB($locationQuery, $debug,$updateFlag);
			foreach ($res as $k=>$v) {
				if(isset($v))
					$results[$k]+=$v;
			}
			
			$results['parse'] ++;
		}
		$results['row'] ++;	
		$row++;
    }
    fclose($handle);
    prettyLog($results);
}

?>
</body>
</html>