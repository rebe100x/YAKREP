<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());
$place = $db->place;

$records = array();

$records[] = array(
	"_id"=>"50517fe1fa9a95040b000007",
	"title"=>"Paris",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);	


$records[] = array(
	"title"=>"Vel d\'Hiv",
	"content" =>"Le vélodrome d\'Hiver de Paris a été érigé en 1909 et détruit en 1959. On l\'appelait familièrement le Vél\' d\'Hiv\'. Il était situé rue Nélaton, dans le 15e arrondissement.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/V%C3%A9lodrome_d'Hiver",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("Velodrome d\'Hiver","Vel\' d\'Hiv\'"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.853537,'lng'=>2.288497),
	"address" => array(
				'street' => 'rue Nélaton',
				'arr' => '15ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75015',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);	

$records[] = array(
	"title"=>"Pépinière d\'Ateliers d\'Art de France",
	"content" =>"La Pépinière a été créée pour accueillir des entreprises ayant moins de cinq années d\'existence et, par conséquent, ne pouvant pas devenir immédiatement adhérents.  L\'objectif est de leur permettre de passer le cap délicat du démarrage de leur activité.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.ateliersdart.com/nos-adherents,2.htm",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("jeunes créateurs","jeunes talents"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.881242,'lng'=>2.30586),
	"address" => array(
				'street' => '6 Rue Jadin',
				'arr' => '17ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75017',
			),
	"contact" => array(
		'tel'=>'01 44 01 08 30',
		'transportation'=>'Métro Monceau',
		'web'=>'http://www.ateliersdart.com',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);


$records[] = array(
	"title"=>"Paris Expo",
	"content" =>"Paris Expo Porte de Versailles est le parc des expositions de toutes vos passions !",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.viparis.com",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("Parc des Expositions Porte de Verailles"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.881242,'lng'=>2.30586),
	"address" => array(
				'street' => '1 place de la Porte de Versailles',
				'arr' => '15ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75015',
			),
	"contact" => array(
		'tel'=>'01 40 68 22 22',
		'transportation'=>'T3, T2, M12 : station Porte de Versailles, Parc des Expositions',
		'web'=>'http://www.viparis.com',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);


$records[] = array(
	"title"=>"bois de Vincennes",
	"content" =>"Avec une superficie de 995 hectares, dont la moitié boisée, c'est le plus grand espace vert parisien. De nombreuses infrastructures occupent le site.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Bois_de_Vincennes",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833086,'lng'=>2.434317),
	"address" => array(
				'street' => '',
				'arr' => '',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
			),
	"contact" => array(
		'transportation'=>'Sept stations du métro sont situées à proximité des bords du bois de Vincennes. Au nord-ouest, la ligne 1 dessert les stations Saint-Mandé, Bérault et Château de Vincennes (son terminus) ; au sud-ouest, la ligne 8 s\'arrête à Porte Dorée, Porte de Charenton, Liberté et Charenton - Écoles.
		Sur le RER A, la gare de Vincennes se trouve à proximité du nord-ouest du bois. Qui plus est, la branche A2 longe le nord-est et l\'est du bois de Vincennes, et s\'arrête aux gares de Fontenay-sous-Bois, Nogent-sur-Marne et Joinville-le-Pont.
		Plusieurs lignes de bus traversent le parc, comme les lignes 46, 112 et 325. Le pourtour du parc est également desservi par plusieurs lignes. De plus, quelques stations de Vélib\' sont réparties le long des frontières.',
		'web'=>'http://www.paris.fr/loisirs/paris-au-vert/bois-de-vincennes/p6566',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"title"=>"bois de Boulogne",
	"content" =>"Couvrant une superficie de 846 hectares environ1 dans l\'ouest de la ville, le bois de Boulogne peut être considéré comme un des « poumons » de la capitale. Deux fois et demie plus grand que Central Park à New York, et 3,3 fois plus grand que Hyde Park à Londres, il est cependant 5,9 fois plus petit que la forêt de Soignes à Bruxelles et occupe seulement la moitié de la surface de la Casa de Campo de Madrid. Le bois de Boulogne occupe le site de l\'ancienne forêt de Rouvray.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Bois_de_Boulogne",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.862456,'lng'=>2.249352),
	"address" => array(
				'street' => '',
				'arr' => '16ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75016',
			),
	"contact" => array(	
		'transportation'=>'RER C : Avenue Henri Martin, Avenue Foch, Métro Ranelagh, Porte Dauphine',
		'web'=>'http://www.paris.fr/loisirs/paris-au-vert/bois-de-boulogne/p6567',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"title"=>"bassin de la Villette",
	"content" =>"Le bassin de la Villette est le plus grand plan d\'eau artificiel de Paris. Il a été mis en eaux le 2 décembre 1808. Situé dans le 19e arrondissement de la capitale, il relie le canal de l\'Ourcq au canal Saint-Martin et constitue l\'un des éléments du réseau des canaux parisiens.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.tourisme93.com/document.php?pagendx=1006",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f")),
	"freeTag"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.88642,'lng'=>2.375057),
	"address" => array(
				'street' => '',
				'arr' => '19ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75019',
			),
	"contact" => array(
		'transportation'=>'Metro ligne 2 Jaurès, Stalingrad, Ligne 5 : Laumière, Ligne 7: Riquet',
		'web'=>'http://fr.wikipedia.org/wiki/Bassin_de_la_Villette',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"title"=>"jardin d\'Acclimatation",
	"content" =>"Parc de loisirs et d\'agrément s\'étendant sur 19 hectares.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Jardin_d'acclimatation_(Paris)/",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f")),
	"freeTag"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.878828,'lng'=>2.264188),
	"address" => array(
				'street' => '',
				'arr' => '16ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75016',
			),
	"contact" => array(
		'transportation'=>'
En voiture
Le parking Vinci du Palais des Congrès et le Jardin d\'Acclimatation vous proposent 50% de réduction sur le stationnement et sur le trajet en Petit Train.
Avec le petit train
Au départ de la Porte Maillot, le Petit Train vous conduit à travers bois jusqu\'à l\'entrée principale du Jardin d\'Acclimatation.
En métro
Station les Sablons, sortie 2, puis prendre la rue d\'Orléans, l\'entrée du Jardin d\'Acclimatation est à 150m.
En bus
Le Jardin d\'Acclimatation est desservi par 6 bus : 43 - 73 - 82 - PC - 174 - 244',
		'web'=>'http://www.jardindacclimatation.fr',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"title"=>"jardin des Plantes",
	"content" =>"Jardin botanique ouvert au public, situé dans le Ve arrondissement de Paris, entre la mosquée de Paris, le campus de Jussieu et la Seine. Il appartient au Muséum national d\'histoire naturelle et est, à ce titre, un campus.
Placé sous le patronage de Buffon jusqu\'en 1788, il s\'étend sur une superficie de 23,5 hectares.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Jardin_des_plantes_de_Paris",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f")),
	"freeTag"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.843889,'lng'=>2.359444),
	"address" => array(
				'street' => '',
				'arr' => '5ème',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75005',
			),
	"contact" => array(
		'transportation'=>'M5, M10, Gare d\'Austerlitz',
		'web'=>'http://www.jardindesplantes.net/',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"title"=>"tunnel des Halles",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.86336,'lng'=>2.34796),
	"address" => array(
				'street' => '',
				'arr' => '1er',
				'city' => 'Paris',
				'state' => 'Paris',
				'area' => 'Ile-de-France',
				'country' => 'France',
				'zip' => '75001',
			),
	"contact" => array(
		'transportation'=>'Metro ligne 4 Etienne Marcel'
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$row = 0;	
foreach($records as $record){
	$res = $place->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row++;
		$place->save($record);
		$place->ensureIndex(array("location"=>"2d"));
		echo $record['_id'];                    
	}
}
echo "<br>".$row." records added.";
                    
                    

?>