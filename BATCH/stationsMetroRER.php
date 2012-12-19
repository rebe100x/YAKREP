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
/* batch for Yakwala to parse "stations de Metro et RER"
 * read csv and create "Place" and "Info" objects
 * call to GMap to enrich location
 * 
 * */
require_once('../LIB/conf.php');

set_time_limit(0);
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
$filenameInput = "./input/metro6.csv";
$row = 0;
$info = new Info();
$origin = 'http://www.data.gouv.fr/donnees/view/Trafic-annuel-entrant-par-station-564116';
$fileTitle = "Trafic annuel entrant par station";
$licence = "licence ouverte";
$access = 1;
$user = 0;
$results = array('row'=>0,'rejected'=>0,'parse'=>0,'duplicate'=>0,'insert'=>0,'getPlaceinDB'=>0,'insertPlace'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,"record"=>array());
$debug = 1;
$updateFlag = empty($_GET['updateFlag'])?0:1;

if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 5000, ";")) !== FALSE) 
    {     
	

		if($row > 0)
		{
			/*
			foreach ($data as $key => &$value) {
				$value = utf8_encode(trim($value));
				echo $key."=>".$value;
			}*/
			
			
			
			if($data[10] == 'Paris'){
					
				
				if(substr($data[3],strlen($data[3])-4,strlen($data[3])) == '-RER')
					$title = substr($data[3],0,strlen($data[3])-4);
				else
					$title = $data[3];
				$info = new Info();
				
				$info->title = 'Trafic annuel de la station de '.$data[2].' '.$title;
			
			
			
				$info->content .=  number_format ( (int)trim($data[4]) , 0 , ',' , ' ' ).' passagers par an';
				
				$info->content .= '<br>Lignes de correspondances: ';
				if ($data[5] != '0')
					$info->content .= $data[5].' ';
				if ($data[6] != '0')
					$info->content .= $data[6].' ';
				if ($data[7] != '0')
					$info->content .= $data[7].' ';
				if ($data[8] != '0')
					$info->content .= $data[8].' ';
				if ($data[9] != '0')
					$info->content .= $data[9];
			
				
				
				$city =	$data[10];
				if($city != 'Paris'){
					$zip = '750'.$data[11];
				}else
					$zip = '';
				
			
				$info->thumb = 'batchthumb/stats.jpg';			
				$info->origin = $origin;
				$info->filesourceTitle = $fileTitle;
				$info->access = $access;
				$info->licence = $licence;
				$info->pubDate = gmmktime();
				$info->dateEndPrint = gmmktime(0, 0, 0, 9, 1, 2013);
				$info->heat = 1;
				$cat = array("TRANSPORT#STATION", "STATISTIQUES");
				$info->setYakCat($cat);
				$info->status = 1;
				$info->print = 1;
				$info->yakType = 3; 
				$info->setZone("PARIS");
				$info->placeName = 'station '.$title; // NOTE, since it goes in the YAKDICO, we need to add the word 'station' before. If we do not all words 'CHATELET' will be detected by semantic
				
				
				
				
				
				
				$locationQuery = 'station '.$data[2].' '.$title . ' ' . $zip . ' ' . $city . ', France';
				
				$cat = array("TRANSPORT#STATION", "STATISTIQUES");
				$info->setYakCat($cat);
			
				
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
				
				
				
			}else{
				$results['rejected'] ++;	
				$results['row'] ++;	
				echo 'rejected'.$data[10];
			}
				$results['parse'] ++;	
		}
		$row++;
		$results['row'] ++;	
		
    }
    fclose($handle);
    //$info->prettyLog($results);
}

?>
</body>
</html>











