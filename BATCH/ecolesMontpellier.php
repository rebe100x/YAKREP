<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd"> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<?php
/* batch for Yakwala to parse "ecoles de Montpellier"
 * read csv and create "Place" and "Info" objects
 * call to GMap to enrich location
 * 
 * */
require_once('../LIB/place.php');
require_once('../LIB/info.php');

ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EffectifSco_2012.csv";
$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;
$place;
$info;
$origin = 'http://opendata.montpelliernumerique.fr/Effectifs-scolaires';
$license = 'http://www.etalab.gouv.fr/pages/licence-ouverte-open-licence-5899923.html';
$access = 1;
$user = 0;

if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {     
		if($row > 1)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			/*$place = new Place();

			$place->title = $fields[3];
			$place->freeTag = $fields[25];
			$place->origin = $origin;
			$place->access = $access;
			$place->license = $license;
			$place->setTagChildren();
			//$place->yakTag["enfants"] = 1;
			//$place->yakTag["couvert, intérieur"] = 1;
			$place->address['street'] = $fields[7].' '.$fields[11];
			$place->address['zipcode'] = $fields[13];
			$place->address['city'] = 'Montpellier';
			$place->address['country'] = 'France';
		
			$place->setTel($fields[5], "tel");
			//$place->contact['tel'] = $fields[5];
			$place->status = 2;
			$place->user = $user;
			$place->setZoneMontpellier();
	
			$query = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
			echo 'Call to GMap: ' . $query;
			echo '<br/>';
			$place->getLocation($query, 0);
		
			$place->setCatEducation();
			$place->setCatYakdico();
		
			if ($fields[2] == 'E')
				$place->setCatElementaire();
			else if ($fields[2] == 'M')
				$place->setCatMaternelle();
					
			echo 'Insertion of the Place in DB';
			echo '<br/>';
			$placeid = $place->saveToMongoDB();*/
		
			$info = new Info();
			//
			//$info->placeid = $placeid;
			//
			$info->setTitle('info rentrée 2012');
			/* A CONFIRMER
			if ($fields[23] < 0)				
				str = 'Fermeture de classes';
			*/
			$info->content = $data[22] . ' classes. Capacité max: ' . $data[21] . ' élèves. Remplissage des classes: ' . $data[24] . '.Ouvertures de classes: ' . $data[23]; 
			$info->origin = $origin;
			$info->access = $access;
			$info->license = $license;
			$info->pubDate = '';
			$info->dateEndPrint = mktime(0, 0, 0, 9, 1, 2013);
			$info->heat = 1;
			$place->setTagChildren();
			//$info->yakTag["enfants"] = 1;
			$info->setCatEcole();
			$info->status = 1;
			$info->print = 1;
			$info->yakType = 3; // A CONFIRMER
			$info->setZoneMontpellier();
			$info->address['street'] = $data[7].' '.$data[11];
			$info->address['zipcode'] = $data[13];
			$info->address['city'] = 'Montpellier';
			$info->address['country'] = 'France';
			$locationQuery = $data[3] . ' ' . $info->address['street'] . ' ' . $info->address['zipcode'] . ' ' . $info->address['city'] . ', ' . $info->address['country'];
		
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
		$row++;
	}
	fclose($handle);
	
	print "<br>________________________________________________<br>
    		museeFrance : done <br>";
    print "Rows : " . ($row-1) . "<br>";
    print "Call to gmap : " . ($insert+$locError) . "<br>";
    print "Location error (call gmap) : " . $locError . "<br>";
    print "Insertions : " . $insert . "<br>";
    print "Updates : " . $update . "<br>";
    print "Doublons : " . $doublon . "<br>";
}













