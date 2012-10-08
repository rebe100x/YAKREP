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

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EtabliPublic_2011_small.csv";
$origin = "http://opendata.montpelliernumerique.fr/Etablissements-publics";
$fileTitle = "Etablissements publics";
$licence = "licence ouverte";

$row = 0;
$results = array('row'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0);
$updateFlag = empty($_GET['updateFlag'])?0:1;
$etsPublics = array('');
$fieldsProcessed = array('');
if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {
        $num = count($data);
        
		if($row > 1)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$place = new Place;

			$place->title = (!empty($data[13]))?$data[13]:$data[5];
			$place->origin = $origin;
			$place->filesourceTitle = $fileTitle;
			$place->licence = $licence;
			$place->setTel($data[16], "tel");
			$place->setMail($data[18]);
			$place->setWeb($data[19]);
			$place->setZoneMontpellier();
			
			$cat = array("GEOLOCALISATION#YAKDICO");
			$place->setYakCat($cat);

			// YakCat
			$place->setYakCat(array($data[9]));


			$locationQuery = $data[10] . ' ' . $data[11] . ' ' . $data[12] . ', France';
		
			$debug = 0;
			$res = $place->saveToMongoDB($locationQuery, $debug,$updateFlag);
			foreach ($res as $k=>$v) {
				if(isset($v))
					$results[$k]+=$v;
			}
			$results['row'] ++;	
		}
		$row++;
    }
    fclose($handle);
    prettyLog($results);
}

?>
</body>
</html>