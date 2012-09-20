<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd"> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<?php
/* batch for Yakwala to parse "ecoles de Montpellier"
 * read csv and create "Place" objects
 * call to GMap to enrich location
 * 
 * */
require_once('../TOOLS/place.php');
require_once('../LIB/info.php');

ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EffectifSco_2012.csv";
$row = 0;
$count = 0;
$ecoles;
$place;
$info;
$origin = 'http://opendata.montpelliernumerique.fr/Effectifs-scolaires';
$license = 'http://www.etalab.gouv.fr/pages/licence-ouverte-open-licence-5899923.html';
$access = 1;
$zone = 2;
$user = 0;

if (($handle = fopen($filenameInput, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {     
		if($row > 0){
			$ecoles[$row] = $data;
		}
		$row++;
    }
    fclose($handle);
}

foreach ($ecoles as $fields)
{
	foreach ($fields as $key => $value) {
				$fields[$key] = utf8_encode($value);
	}
			
	if ($count > 0)
	{
		$place = new Place();

		$place->title = $fields[3];
		$place->freeTag = $fields[25];
		$place->origin = $origin;
		$place->access = $access;
		$place->license = $license;
		$place->yakTag["enfants"] = 1;
		$place->yakTag["couvert, intérieur"] = 1;
		$place->address['street'] = $fields[7].' '.$fields[11];
		$place->address['zipcode'] = $fields[13];
		$place->address['city'] = 'Montpellier';
		$place->address['country'] = 'France';
		
		$place->contact['tel'] = $fields[5];
		$place->status = 2;
		$place->user = $user;
		$place->zone = $zone;
		
		$query = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
		echo 'Appel à GMap';
		echo '<br/>';
		$place->getLocation($query, 0);
		
		$place->setCatEducation();
		$place->setCatDico();
		
		if ($fields[2] == 'E')
			$place->setCatElementaire();
		else if ($fields[2] == 'M')
			$place->setCatMaternelle();
		
		echo 'Insertion de la place dans la base de données';
		echo '<br/>';
		$placeId = $place->saveToMongoDB();
		
		$info = new Info();
		$info->placeId = $placeId;
		$info->title = 'info rentrée 2012';
		/* A CONFIRMER
		if ($fields[23] < 0)
			str = 'Fermeture de classes';
		*/
		$info->content = $fields[22] . ' classes. Capacité max: ' . $fields[21] . ' élèves. Remplissage des classes: ' . $fields[24] . '.Ouvertures de classes: ' . $fields[23]; 
		$info->origin = $origin;
		$info->access = $access;
		$info->license = $license;
		$info->pubDate = '';
		$info->dateEndPrint = mktime(0, 0, 0, 9, 1, 2013);
		$info->heat = 1;
		$info->yakTag["enfants"] = 1;
		$info->setCatEcole();
		$info->status = 1;
		$info->print = 1;
		$info->yakType = 3; // A CONFIRMER
		$info->zone = $zone;
		$info->zone = $zone;
		echo "Insertion de l'info dans la base de données";
		echo '<br/>';
		$info->saveToMongoDB();
	}
	$count++;
}

//A modifier plus tard apres avoir gere les doublons
echo $count.' données ont été insérées';













