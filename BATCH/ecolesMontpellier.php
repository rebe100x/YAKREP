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
/* batch for Yakwala to parse "ecoles de Montpellier"
 * read csv and create "Place" and "Info" objects
 * call to GMap to enrich location
 * 
 * */
require_once('../LIB/conf.php');


ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EffectifSco_2012.csv";
$row = 0;
$insert = 0;
$update = 0;
$doublon = 0;
$locError = 0;
$i = 0;
$place;
$info;
$origin = 'http://opendata.montpelliernumerique.fr/Effectifs-scolaires';
$fileTitle = "Effectifs scolaires";
$licence = 'licence ouverte';
$access = 1;
$user = 0;
$results = array('row'=>0,'rejected'=>0,'parse'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,"record"=>array());
$debug = 1;
$updateFlag = empty($_GET['updateFlag'])?0:1;

if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {     
		if($row > 0)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
		
			$info = new Info();
			$info->title = $data[3];
			/* A CONFIRMER
			if ($data[23] < 0)				
				str = 'Fermeture de classes';
			*/
			$info->content = $data[22] . ' classes. Capacité max: ' . $data[21] . ' élèves. Remplissage des classes: ' . $data[24] . '.Ouvertures de classes: ' . $data[23]; 
			$info->freeTag = $data[25];
			$info->origin = $origin;
			$info->filesourceTitle = $fileTitle;
			$info->access = $access;
			$info->licence = $licence;
			$info->pubDate = '';
			$info->dateEndPrint = mktime(0, 0, 0, 9, 1, 2013);
			$info->heat = 1;
			$info->setTagChildren();
			$cat = array("EDUCATION", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO", "EDUCATION#ECOLE");
			$info->setYakCat($cat);
			$info->status = 1;
			$info->print = 1;
			$info->yakType = 3; // A CONFIRMER
			$info->setTel($data[5]);
			$info->setZone("MONTPELLIER");
			$info->placeName = $data[3];
			
			
			$locationQuery = $data[7].' '.$data[11] . ', ' . $data[13] . ', France';
			
			//echo $locationQuery;
			$res = $info->saveToMongoDB($locationQuery, $debug,$updateFlag);
			
			
			if(!empty($res['error'])){
				echo $res['error'];
				echo '<br><b>BATCH FAILLED</b><br>';
				exit;
			}
			
			foreach ($res as $k=>$v) {
				if(isset($v))
					$results[$k]+=$v;
			}
			
			
			
			$results['parse'] ++;	
			
		}
		$row++;
		$results['row'] ++;	
	}
	fclose($handle);
    prettyLog($results);
}

?>
</body>
</html>











