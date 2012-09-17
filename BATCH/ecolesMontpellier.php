<?php
/* batch for Yakwala to parse "ecoles de Montpellier"
 * read csv and create "Place" objects
 * call to GMap to enrich location
 * 
 * */
require_once('../TOOLS/place.php');

ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EffectifSco_2012.csv";
$row = 0;
$count = 0;
$ecoles;
$place;

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
	if ($count > 0)
	{
		$place = new Place();

		$place->title = $fields[3];
		$place->content = $fields[4];
		$place->origin = 'http://opendata.montpelliernumerique.fr/Effectifs-scolaires';
		$place->access = 1;
		$place->license = 'http://www.etalab.gouv.fr/pages/licence-ouverte-open-licence-5899923.html';
		$place->yakTag["enfants"] = 1;
		$place->yakTag["couvert, intérieur"] = 1;
		$place->address['street'] = $fields[7].' '.$fields[11];
		$place->address['zipcode'] = $fields[13];
		$place->address['city'] = 'Montpellier';
		$place->address['country'] = 'France';
		
		$place->contact['tel'] = $fields[5];
		$place->status = 2;
		$place->user = 0;
		$place->zone = 2;
		
		$query = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
		echo 'Appel à GMap';
		echo '<br/>';
		$place->getLocation($query, 0);
		
		$place->setCatEducation();
		
		echo 'Insertion dans la base de données';
		echo '<br/>';
		$place->saveToMongoDB();
	}
	$count++;
}













