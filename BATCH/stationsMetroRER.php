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
require_once('../LIB/place.php');
require_once('../LIB/info.php');

ini_set('display_errors',1);
$filenameInput = "./input/stationsMetroRER.csv";
$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;
$count = 0;
$place;
$info;
$origin = 'http://www.data.gouv.fr/donnees/view/Trafic-annuel-entrant-par-station-564116';
$licence = 'http://www.data.gouv.fr/Licence-Ouverte-Open-Licence';
$access = 1;
$user = 0;

if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {     
		if($row > 2)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			$place = new Place();

			$place->setTitle('Station '.$data[3]);
		
			$place->content = 'Lignes de correspondances: ';
			if ($data[5] != '0')
				$place->content .= $data[5].' ';
			if ($data[6] != '0')
				$place->content .= $data[6].' ';
			if ($data[7] != '0')
				$place->content .= $data[7].' ';
			if ($data[8] != '0')
				$place->content .= $data[8].' ';
			if ($data[9] != '0')
				$place->content .= $data[9];
		
			$place->origin = $origin;
			$place->access = $access;
			$place->licence = $licence;
			$place->address['zipcode'] = '750'.$data[11];
			$place->address['city'] = $data[10];
			$place->address['country'] = 'France';
		
			$place->status = 2;
			$place->user = $user;
			$place->setZoneParis();
		
			$locationQuery = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
		
			$place->setCatStations();
		
			$debug = 0;
			switch ($place->saveToMongoDB($locationQuery, $debug, false)) 
			{
				case '1':
					$locError++;
					break;
				case '2':
					print "updated <br>";
					$update++;
					break;
				case '3':
					print "doublon <br>";
					$doublon++;
					break;
				default:
					$insert++;
					print_r($place->prettyPrint() . "\n<hr/>\n");
					break;
			}
			
			$info = new Info();
			$info->setTitle($place->title);
			$info->content = $data[4];
			$info->origin = $origin;
			$info->access = $access;
			$info->licence = $licence;
			$info->pubDate = '';
			$info->dateEndPrint = mktime(0, 0, 0, 9, 1, 2013);
			//$info->heat = 1;
			$info->setCatYakdico();
			$info->status = 1;
			$info->print = 1;
			$info->yakType = 3;
			$info->setZoneParis();

			$debug = 0;
			switch ($info->saveToMongoDB($locationQuery, $debug, false)) 
			{
				case '1':
					$locError++;
					break;
				case '2':
					print "updated <br>";
					$update++;
					break;
				case '3':
					print "doublon <br>";
					$doublon++;
					break;
				default:
					$insert++;
					print_r($info->prettyPrint() . "\n<hr/>\n");
					break;
			}
		}
		$row++;
    }
    print "<br/> doublon : $doublon - insert : $insert - update : $update - error loc : $locError <br>";
    fclose($handle);
    print_r("offreCulturelle done.\n");
}

?>
</body>
</html>











