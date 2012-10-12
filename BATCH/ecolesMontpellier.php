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
$filenameInput = "./input/VilleMTP_MTP_EffectifSco_2012_small.csv";
$row = 0;
$i = 0;
$place;
$info;
$origin = 'http://opendata.montpelliernumerique.fr/Effectifs-scolaires';
$fileTitle = "Effectifs scolaires";
$licence = 'licence ouverte';
$access = 1;
$user = 0;
$results = array('row'=>0,'rejected'=>0,'parse'=>0,'duplicate'=>0,'insert'=>0,'getPlaceinDB'=>0,'insertPlace'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,"record"=>array());
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
				$value = utf8_encode(trim($value));
			}
		
			$info = new Info();
			//$info->title = $data[3];
			$info->title = "Rentrée 2012 - ".$info->title = $data[3];;
			/* A CONFIRMER
			if ($data[23] < 0)				
				str = 'Fermeture de classes';
			*/
			
			if((int)$data[22] == 0 ){
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			elseif((int)$data[22] == 1 )
				$nbClasses = $data[22] . " classe.";
			else
				$nbClasses = $data[22] . " classes.";
			
			$nbClasses = $nbClasses.$nbEnfantsMax = " (max: " . $data[21]." élèves)";
			
			if((int)$data[23] == 0)
				$creationClasses = "Pas de création de classe pour cette rentrée.";
			elseif((int)$data[23] == 1)
				$creationClasses = $data[23]." création de classe.";
			else
				$creationClasses = $data[23]." créations de classe.";

			$infoComplementaire = $data[24];
			
			$info->content = implode('<br>',array($nbClasses,$infoComplementaire,$creationClasses)); 
			
			if($data[25] ==  'O')
				$info->freeTag = array("ZEP","Zone d'Education Prioritaire","Carte scolaire");
			else
				$info->freeTag = array("Carte scolaire");
			
			$info->thumb = 'batchthumb/rentree.jpg';			
			$info->origin = $origin;
			$info->filesourceTitle = $fileTitle;
			$info->access = $access;
			$info->licence = $licence;
			$info->pubDate = gmmktime();
			$info->dateEndPrint = gmmktime(0, 0, 0, 9, 1, 2013);
			$info->heat = 1;
			$cat = array("EDUCATION", "EDUCATION#ECOLE");
			$info->setYakCat($cat);
			$info->status = 1;
			$info->print = 1;
			$info->yakType = 3; 
			$info->setTel($data[5]);
			$info->setZone("MONTPELLIER");
			$info->placeName = $data[3];
			
			
			$locationQuery = $data[7].' '.$data[11] . ', ' . $data[13] . ', Montpellier, France';
			
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
    $info->prettyLog($results);
}

?>
</body>
</html>











