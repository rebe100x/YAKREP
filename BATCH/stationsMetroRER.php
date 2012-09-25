<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd"> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

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
$stations;
$place;
$info;
$origin = 'http://www.data.gouv.fr/donnees/view/Trafic-annuel-entrant-par-station-564116';
$license = 'http://www.data.gouv.fr/Licence-Ouverte-Open-Licence';
$access = 1;
$user = 0;

if (($handle = fopen($filenameInput, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {     
		if($row > 1){
			$stations[$row] = $data;
		}
		$row++;
    }
    fclose($handle);
}

foreach ($stations as $fields)
{
	foreach ($fields as $key => $value) {
				$fields[$key] = utf8_encode($value);
	}
			
	if ($count > 0)
	{
		$place = new Place();

		$place->setTitle('Station '.$fields[3]);
		
		$place->content = 'Lignes de correspondances: ';
		if ($fields[5] != '0')
			$place->content .= $fields[5].' ';
		if ($fields[6] != '0')
			$place->content .= $fields[6].' ';
		if ($fields[7] != '0')
			$place->content .= $fields[7].' ';
		if ($fields[8] != '0')
			$place->content .= $fields[8].' ';
		if ($fields[9] != '0')
			$place->content .= $fields[9];
		
		$place->origin = $origin;
		$place->access = $access;
		$place->license = $license;
		$place->address['zipcode'] = '750'.$fields[11];
		$place->address['city'] = $fields[10];
		$place->address['country'] = 'France';
		
		$place->status = 2;
		$place->user = $user;
		$place->setZoneParis();
		
		$locationQuery = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
		/*echo 'Call to GMap: ' . $query;
		echo '<br/>';
		$place->getLocation($query, 0);*/
		
		//$place->setCatStation();
		
		echo 'Insertion of the Place in DB';
		echo '<br/>';
		$debug = 0;
		switch ($place->saveToMongoDB($locationQuery, $debug)) 
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
				print "insert (1 call to gmap)<br>";
				$insert++;
				break;
		}
		
		$info = new Info();
		//$info->placeid = $placeid;
		$info->setTitle($place->title);
		$info->content = $fields[4];
		$info->origin = $origin;
		$info->access = $access;
		$info->license = $license;
		$info->pubDate = '';
		$info->dateEndPrint = mktime(0, 0, 0, 9, 1, 2013);
		//$info->heat = 1;
		$info->setCatYakdico();
		$info->status = 1;
		$info->print = 1;
		$info->yakType = 3;
		$info->setZoneParis();
		echo "Insertion of the Info in DB";
		echo '<br/>';
		$debug = 0;
		switch ($info->saveToMongoDB($locationQuery, $debug)) 
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
				print "insert (1 call to gmap)<br>";
				$insert++;
				break;
		}
	}
	$count++;
}

echo $count.' data has been inserted';













