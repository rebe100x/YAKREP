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
	"_id" => new MongoId("50896788fa9a954c01000005"),
	"title"=>"le vieux Garges",
	"content" =>"Quartier de Garges-lès-Gonesse",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50896423fa9a954c01000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.971795,'lng'=>2.399329),
	"formatted_address"=> "95140 Garges-lès-Gonesse, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'Garges-lès-Gonesse',
				'state'=>"Val d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'95140',
			),			
	"status" => 1,
	"user" => 0, 
	"zone"=> 13,
	
	
);

$records[] = array(
	"_id" => new MongoId("5089648bfa9a954c01000002"),
	"title"=>"Buffalo Grill",
	"content" =>"Chaine de restaurants de viande grillée",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50896423fa9a954c01000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>49.020601,'lng'=>2.466612),
	"formatted_address"=> "6 rue Léonard de Vinci, rue Joseph Cugnot, 95190 Goussainville, France",
	"address" => array(
				'street_number'=>'6',
				'street'=>"rue Léonard de Vinci",
				'arr'=>'',
				'city'=>'Goussainville',
				'state'=>"Val d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'95190',
			),			
	"status" => 1,
	"user" => 0, 
	"zone"=> 13,
	
	
);


$records[] = array(
	"_id" => new MongoId("508962b1fa9a95980b000000"),
	"title"=>"lycée Georges-Braque",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50896395fa9a950007000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.950493,'lng'=>2.250671),
	"formatted_address"=> "21 Rue Victor Puiseux, 95100 Argenteuil, France",
	"address" => array(
				'street_number'=>'21',
				'street'=>"Rue Victor Puiseux",
				'arr'=>'',
				'city'=>'Argenteuil',
				'state'=>"Val d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'95100',
			),			
	"status" => 1,
	"user" => 0, 
	"zone"=> 13,
	
	
);



$records[] = array(
	"_id" => new MongoId("50896788fa9a954c01000006"),
	"title"=>"Aéroport du Bourget",
	"content" =>"Aéroport de Paris - Le Bourget",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50895efefa9a95dc07000000"),new MongoId("5077ebb1fa9a95600d0001dc")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.964329,'lng'=>2.439969),
	"formatted_address"=> "Aéroport de Paris - Le Bourget, Bonneuil-en-France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'Le Bourget',
				'state'=>'Seine-Saint-Denis',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'93350',
			),			
	"status" => 1,
	"user" => 0, 
	"zone"=> 12,
	
	
);

$records[] = array(
	"_id" => new MongoId("508960e4fa9a95dc07000002"),
	"title"=>"Aéroport d'Orly",
	"content" =>"Aéroport de Paris - Orly",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50895efefa9a95dc07000000"),new MongoId("5077ebb1fa9a95600d0001dc")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.728398,'lng'=>2.36694),
	"formatted_address"=> "Aéroport de Paris - Orly, Essonne, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'Orly',
				'state'=>'Essone',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'94396',
			),			
	"status" => 1,
	"user" => 0, 
	"zone"=> 9,
	
	
);

$records[] = array(
	"_id" => new MongoId("508960e4fa9a95dc07000003"),
	"title"=>"Roissy",
	"content" =>"Aéroport de Paris - Roissy , Aéroport Charles-de-Gaulle",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50895efefa9a95dc07000000"),new MongoId("5077ebb1fa9a95600d0001dc")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>49.009726,'lng'=>2.547772),
	"formatted_address"=> "Aéroport Charles-de-Gaulle, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'Roissy-en-France',
				'state'=>"Val d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>' 95700',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 13,
	
	
);


$records[] = array(
	"_id" => new MongoId("50841e8c1d22b32a6100000a"),
	"title"=>"Le Point éphémère",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("506479f54a53042191010000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.881608,'lng'=>2.368623),
	"formatted_address"=> "206 Quai de Valmy, Paris, France",
	"address" => array(
				'street_number'=>'206',
				'street'=>"Quai de Valmy",
				'arr'=>'10ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75010',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("50841d901d22b30168000007"),
	"title"=>"Galeries Lafayette",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.873441,'lng'=>2.332132),
	"formatted_address"=> "40 Boulevard Haussmann, 75009 Paris, France",
	"address" => array(
				'street_number'=>'40',
				'street'=>"Boulevard Haussmann",
				'arr'=>'9th arrondissement of Paris',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75009',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);


$records[] = array(
	"_id" => new MongoId("5087def6fa9a951c0d000016"),
	"title"=>"Val-d'Oise",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>49.06159010,'lng'=>2.15813510),
	"formatted_address"=> "Val-d'Oise, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>"Val-d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'95000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 13,
	
	
);

$records[] = array(
	"_id" => new MongoId("5087def6fa9a951c0d000017"),
	"title"=>"Val-de-Marne",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.79314260,'lng'=>2.47403370),
	"formatted_address"=> "Val-de-Marne, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Val-de-Marne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'94000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 12,
	
	
);

$records[] = array(
	"_id" => new MongoId("5087def6fa9a951c0d000018"),
	"title"=>"Seine-Saint-Denis",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.91374550,'lng'=>2.48457290),
	"formatted_address"=> "Seine-Saint-Denis, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Seine-Saint-Denis',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'93000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 11,
	
	
);


$records[] = array(
	"_id" => new MongoId("5087def6fa9a951c0d000019"),
	"title"=>"Hauts-de-Seine",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8285080,'lng'=>2.21880680),
	"formatted_address"=> "Hauts-de-Seine, France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Hauts-de-Seine',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'92000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 10,
	
	
);

$records[] = array(
	"_id" => new MongoId("50813b26fa9a950c14000003"),
	"title"=>"Essonne",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.45856980,'lng'=>2.15694160),
	"formatted_address"=> "Essonne, 91000 France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Essonne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'91000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 9,
	
	
);

$records[] = array(
	"_id" => new MongoId("50813b26fa9a950c14000004"),
	"title"=>"Yvelines",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.78509390,'lng'=>1.82565720),
	"formatted_address"=> "Yvelines, 78000 France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Yvelines',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'78000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 8,
	
	
);
$records[] = array(
	"_id" => new MongoId("50811ceffa9a95280f000037"),
	"title"=>"Seine-et-Marne",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8410820,'lng'=>2.9993660),
	"formatted_address"=> "Seine-et-Marne, 77000 France",
	"address" => array(
				'street_number'=>'',
				'street'=>"",
				'arr'=>'',
				'city'=>'',
				'state'=>'Seine-et-Marne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'77000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 7,
	
	
);


$records[] = array(
	"_id" => new MongoId("507d22471d22b30b44000016"),
	"title"=>"Muséum d'histoire naturelle",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8414229,'lng'=>2.3562147),
	"formatted_address"=> "36 Rue Geoffroy-Saint-Hilaire, 75005 Paris",
	"address" => array(
				'street_number'=>'36',
				'street'=>"Rue Geoffroy-Saint-Hilaire",
				'arr'=>'5',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75005',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);


$records[] = array(
	"_id" => new MongoId("507d15161d22b3964e000077"),
	"title"=>"Muséum d'Histoire Naturelle",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8414229,'lng'=>2.3562147),
	"formatted_address"=> "36 Rue Geoffroy-Saint-Hilaire, 75005 Paris",
	"address" => array(
				'street_number'=>'36',
				'street'=>"Rue Geoffroy-Saint-Hilaire",
				'arr'=>'5',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75005',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	//"_id" => new MongoId("507d12ee1d22b30c44000041"),
	"title"=>"Muséum d'Histoire Naturelle",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8414229,'lng'=>2.3562147),
	"formatted_address"=> "36 Rue Geoffroy-Saint-Hilaire, 75005 Paris",
	"address" => array(
				'street_number'=>'36',
				'street'=>"Rue Geoffroy-Saint-Hilaire",
				'arr'=>'5',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75005',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);


$records[] = array(
	"_id" => new MongoId("507d12ee1d22b30c44000041"),
	"title"=>"Mosquée de Paris",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.842054,'lng'=>2.355075),
	"formatted_address"=> "6 Rue Georges Desplas, 75005 Paris",
	"address" => array(
				'street_number'=>'6',
				'street'=>"Rue Georges Desplas",
				'arr'=>'5',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75005',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);


$records[] = array(
	"_id" => new MongoId("507d11191d22b30c44000040"),
	"title"=>"voies sur berge",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.861637,'lng'=>2.327875),
	"formatted_address"=> "Voie Georges Pompidou, 75001 Paris",
	"address" => array(
				'street_number'=>'1',
				'street'=>"Voie Georges Pompidou",
				'arr'=>'1',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75001',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507d123e1d22b3954e000025"),
	"title"=>"voies sur berges",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.861637,'lng'=>2.327875),
	"formatted_address"=> "Voie Georges Pompidou, 75001 Paris",
	"address" => array(
				'street_number'=>'1',
				'street'=>"Voie Georges Pompidou",
				'arr'=>'1',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75001',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000031"),
	"title"=>"la Samaritaine",
	"content" =>"Grands magasins de La Samaritaine",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/La_Samaritaine	",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.858489,'lng'=>2.34266),
	"formatted_address"=> "1 rue du Pont Neuf, 75001 Paris",
	"address" => array(
				'street_number'=>'1',
				'street'=>"Rue du Pont Neuf",
				'arr'=>'1',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75001',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);
$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000032"),
	"title"=>"Beaubourg",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.centrepompidou.fr/",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8612422,'lng'=>2.3533372),
	"formatted_address"=> "41 Rue de Rambuteau, 75004 Paris",
	"address" => array(
				'street_number'=>'41',
				'street'=>"Rue de Rambuteau",
				'arr'=>'4',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000033"),
	"title"=>"musée Pompidou",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.centrepompidou.fr/",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8612422,'lng'=>2.3533372),
	"formatted_address"=> "41 Rue de Rambuteau, 75004 Paris",
	"address" => array(
				'street_number'=>'41',
				'street'=>"Rue de Rambuteau",
				'arr'=>'4',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000034"),
	"title"=>"restaurant la Fidélité",
	"content" =>"Historique bistrot de nuit du 10ème arrondissement, lové entre les ruelles du faubourg Saint-Denis et les Grands Boulevards, La Fidélité n’a jamais cessé d’attirer les noctambules avertis, curieux et autres gourmands de passage.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://lafidelite.com/site/index.php",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.874708,'lng'=>2.356736),
	"formatted_address"=> "12 Rue de la Fidélité, 75010 Paris",
	"address" => array(
				'street_number'=>'12',
				'street'=>"Rue de la Fidélité",
				'arr'=>'10',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75010',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000035"),
	"title"=>"salon de l’automobile",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833538,'lng'=>2.287461),
	"formatted_address"=> "1 Place de la Porte de Versailles, 75015 Paris",
	"address" => array(
				'street_number'=>'',
				'street'=>"Place de la Porte de Versailles",
				'arr'=>'7',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75015',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507c02ba1d22b30a44000036"),
	"title"=>"salon de l’auto",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833538,'lng'=>2.287461),
	"formatted_address"=> "1 Place de la Porte de Versailles, 75015 Paris",
	"address" => array(
				'street_number'=>'',
				'street'=>"Place de la Porte de Versailles",
				'arr'=>'7',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75015',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf77e1d22b3944e000009"),
	"title"=>"salon de l’Agriculture",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833538,'lng'=>2.287461),
	"formatted_address"=> "1 Place de la Porte de Versailles, 75015 Paris",
	"address" => array(
				'street_number'=>'',
				'street'=>"Place de la Porte de Versailles",
				'arr'=>'7',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75015',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf5241d22b3064400000d"),
	"title"=>"Musée d'Orsay",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("50535d5bfa9a95ac0d0000b6")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.8597777,'lng'=>2.3254996),
	"formatted_address"=> "1 place de l'Hôtel de Ville - 75017 Paris",
	"address" => array(
				'street_number'=>'',
				'street'=>"Rue de Lille",
				'arr'=>'7',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75007',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf4571d22b3964e00000b"),
	"title"=>"mairie de Paris",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "www.paris.fr/‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.857485,'lng'=>2.351517),
	"formatted_address"=> "1 place de l'Hôtel de Ville - 75017 Paris",
	"address" => array(
				'street_number'=>'1',
				'street'=>"Place de l'Hôtel de Ville",
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf2fc1d22b30a44000030"),
	"title"=>"l’espace Champerret",
	"content" =>"L'Espace Champerret est un espace près de la porte de Champerret où se tiennent des congrès et salons. Il est géré par l'entité Viparis.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.viparis.com/Viparis/salon-paris/site/Espace-Champerret/fr/5‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.886787,'lng'=>2.289015),
	"formatted_address"=> "6 rue Jean Oestreicher - 75017 Paris",
	"address" => array(
				'street_number'=>'6',
				'street'=>'rue Jean Oestreicher',
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75017',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf0f41d22b3064400000c"),
	"title"=>"l’île Saint-Germain",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.823,'lng'=>2.248868),
	"formatted_address"=> "île Saint-Germain",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Issy-les-Moulineaux',
				'state'=>'Hauts-de-Seine',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'92130',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf0d81d22b3094400000e"),
	"title"=>"l’île Saint Germain",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.823,'lng'=>2.248868),
	"formatted_address"=> "île Saint-Germain",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Issy-les-Moulineaux',
				'state'=>'Hauts-de-Seine',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'92130',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf0841d22b3964e00000a"),
	"title"=>"l’île Seguin",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.823904,'lng'=>2.233032),
	"formatted_address"=> "île Seguin",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Sèvres',
				'state'=>'Hauts-de-Seine',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'92310',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);


$records[] = array(
	"_id" => new MongoId("507bf0031d22b30844000016"),
	"title"=>"l’île de la Cité",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.854847,'lng'=>2.347037),
	"formatted_address"=> "île de la Cité",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf1b21d22b3954e000014"),
	"title"=>"l’île St-Louis",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851854,'lng'=>2.356435),
	"formatted_address"=> "île Saint-Louis",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bf19a1d22b30c44000035"),
	"title"=>"l’île St Louis",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851854,'lng'=>2.356435),
	"formatted_address"=> "île Saint-Louis",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507befac1d22b30144000012"),
	"title"=>"l’île Saint Louis",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851854,'lng'=>2.356435),
	"formatted_address"=> "île Saint-Louis",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507bef931d22b30344000010"),
	"title"=>"l’île Saint-Louis",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851854,'lng'=>2.356435),
	"formatted_address"=> "île Saint-Louis",
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'4ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507beea31d22b3944e000008"),
	"title"=>"la Bastille",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.853294,'lng'=>2.369331),
	"formatted_address"=> "Place de la Bastille 75011 Paris",
	"address" => array(
				'street_number'=>'',
				'street'=>'Place de la Bastille',
				'arr'=>'11ème',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75011',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);

$records[] = array(
	"_id" => new MongoId("507beddf1d22b30b4400000a"),
	"title"=>"Cour des Comptes‎",
	"content" =>"La Cour des comptes est une juridiction financière de l'ordre administratif en France, chargée principalement de contrôler la régularité des comptes publics, de l'État, des établissements publics nationaux, des entreprises publiques, de la sécurité sociale, ainsi que des organismes privés bénéficiant d'une aide de l'État ou faisant appel à la générosité du public. Elle informe le Parlement, le Gouvernement et l'opinion publique sur la régularité des comptes.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "ccomptes.fr‎",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.856499,'lng'=>2.352187),
	"formatted_address"=> "13 Rue Cambon 75001 Paris",
	"address" => array(
				'street_number'=>'13',
				'street'=>'Rue Cambon',
				'arr'=>'1th',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75001',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1,
	
	
);





$records[] = array(
	"_id" => new MongoId("507becbf1d22b30a4400002f"),
	"title"=>"Conseil de Paris‎",
	"content" =>"Le conseil de Paris est l'assemblée délibérante de Paris, possédant à la fois les attributions d'un conseil municipal (Paris en tant que commune) et celles d'un conseil général (Paris en tant que département), définies par la loi PLM du 31 décembre 1982. Paris est en effet la seule ville de France à être à la fois une commune et un département, depuis la loi du 10 juillet 1964 portant réorganisation de la région parisienne.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Conseil_de_Paris",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.856499,'lng'=>2.352187),
	"formatted_address"=> "5 Rue de Lobau, 75004 Paris",
	"address" => array(
				'street_number'=>'5',
				'street'=>'Rue de Lobau',
				'arr'=>'4th',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75004',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);


$records[] = array(
	"_id" => new MongoId("507be8061d22b30c44000016"),
	"title"=>"Galerie Thaddaeus Ropac‎",
	"content" =>"Thaddaeus Ropac (born in 1960, Klagenfurt, Austria), is a gallerist specializing in European and American Contemporary art. He owns the Galerie Thaddaeus Ropac based in Salzburg (Austria) and Paris (France).",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://ropac.net/",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000"),new MongoId("504d89cffa9a957004000001")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.86079,'lng'=>2.36371),
	"formatted_address"=> "7 Rue Debelleyme 75003 Paris",
	"address" => array(
				'street_number'=>'7',
				'street'=>'Rue Debelleyme',
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75003',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);
$records[] = array(
	"_id" => new MongoId("507bdf3e1d22b3094400000c"),
	"title"=>"Buttes Chaumont",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.880084,'lng'=>2.38182),
	"address" => array(
				'street_number'=>'',
				'street'=>'1 rue Botzaris',
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75019',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);
$records[] = array(
	"_id" => new MongoId("507bdf3e1d22b3094400000d"),
	"title"=>"Buttes-Chaumont",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.880084,'lng'=>2.38182),
	"address" => array(
				'street_number'=>'',
				'street'=>'1 rue Botzaris',
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75019',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);	


$records[] = array(
	"_id" => new MongoId("507eaca21d22b3954e0000e0"),
	"title"=>"Montpellier",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("507e5a9a1d22b30c44000068")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>43.6107690,'lng'=>3.8767160),
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Montpellier',
				'state'=>'Hérault',
				'area'=>'Languedoc-Roussillon',
				'country'=>'France',
				'zip'=>'34000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 2
	
);	

$records[] = array(
	"_id" => new MongoId("50517fe1fa9a95040b000007"),
	"title"=>"Paris",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001")), // ATTENTION NOT IN YAKCAT VILLE : TOO BIG
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'75000',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);	


$records[] = array(
	"_id" => new MongoId("507e814dfa9a95e00c000000"),
	"title"=>"Namur",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("507e5a9a1d22b30c44000068")), 
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>50.465317,'lng'=>4.867709),
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Namur',
				'state'=>'',
				'area'=>'Région wallonne',
				'country'=>'Belgique',
				'zip'=>'',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 5
	
);	

$records[] = array(
	"_id" => new MongoId("507e9ce11d22b3944e00005a"),
	"title"=>"Bruxelles",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("507e5a9a1d22b30c44000068")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>50.850337,'lng'=>4.351699),
	"address" => array(
				'street_number'=>'',
				'street'=>'',
				'arr'=>'',
				'city'=>'Bruxelles',
				'state'=>'',
				'area'=>'Bruxelles-Capitale',
				'country'=>'Belgique',
				'zip'=>'',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 4
	
);	


$records[] = array(
	"_id" => new MongoId("5059750efa9a954014000058"),
	"title"=>"Vel d'Hiv",
	"content" =>"Le vélodrome d'Hiver de Paris a été érigé en 1909 et détruit en 1959. On l'appelait familièrement le Vél' d'Hiv\'. Il était situé rue Nélaton, dans le 15e arrondissement.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("Velodrome d'Hiver","Vel' d'Hiv"),
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
	"_id" => new MongoId("5059750efa9a95401400005a"),
	"title"=>"Pépinière d'Ateliers d'Art de France",
	"content" =>"La Pépinière a été créée pour accueillir des entreprises ayant moins de cinq années d'existence et, par conséquent, ne pouvant pas devenir immédiatement adhérents.  L'objectif est de leur permettre de passer le cap délicat du démarrage de leur activité.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.ateliersdart.com/nos-adherents,2.htm",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
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
	"_id" => new MongoId("506aa596fa9a95840f000005"),
	"title"=>"Paris Expo",
	"content" =>"Paris Expo Porte de Versailles est le parc des expositions de toutes vos passions !",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.viparis.com",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("Parc des Expositions Porte de Verailles"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833538,'lng'=>2.287461),
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
	"_id" => new MongoId("506aa9b91d22b3496a00001e"),
	"title"=>"parc des Expositions",
	"content" =>"Paris Expo Porte de Versailles est le parc des expositions de toutes vos passions !",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.viparis.com",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001"),new MongoId("5056b7aafa9a95180b000000")),
	"freeTag"=>array("Parc des Expositions Porte de Versailles"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.833538,'lng'=>2.287461),
	"address" => array(
				'street' => '1 Place de la Porte de Versailles',
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
	"_id" => new MongoId("5059750efa9a95401400005e"),
	"title"=>"bois de Vincennes",
	"content" =>"Avec une superficie de 995 hectares, dont la moitié boisée, c'est le plus grand espace vert parisien. De nombreuses infrastructures occupent le site.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Bois_de_Vincennes",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f"),new MongoId("504d89f4fa9a958808000001")),
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
		'transportation'=>"Sept stations du métro sont situées à proximité des bords du bois de Vincennes. Au nord-ouest, la ligne 1 dessert les stations Saint-Mandé, Bérault et Château de Vincennes (son terminus) ; au sud-ouest, la ligne 8 s'arrête à Porte Dorée, Porte de Charenton, Liberté et Charenton - Écoles.
		Sur le RER A, la gare de Vincennes se trouve à proximité du nord-ouest du bois. Qui plus est, la branche A2 longe le nord-est et l'est du bois de Vincennes, et s'arrête aux gares de Fontenay-sous-Bois, Nogent-sur-Marne et Joinville-le-Pont.
		Plusieurs lignes de bus traversent le parc, comme les lignes 46, 112 et 325. Le pourtour du parc est également desservi par plusieurs lignes. De plus, quelques stations de Vélib' sont réparties le long des frontières.',
		'web'=>'http://www.paris.fr/loisirs/paris-au-vert/bois-de-vincennes/p6566",
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"_id" => new MongoId("507bfe171d22b3954e000017"),
	"title"=>"bois de Boulogne",
	"content" =>"Couvrant une superficie de 846 hectares environ1 dans l'ouest de la ville, le bois de Boulogne peut être considéré comme un des « poumons » de la capitale. Deux fois et demie plus grand que Central Park à New York, et 3,3 fois plus grand que Hyde Park à Londres, il est cependant 5,9 fois plus petit que la forêt de Soignes à Bruxelles et occupe seulement la moitié de la surface de la Casa de Campo de Madrid. Le bois de Boulogne occupe le site de l'ancienne forêt de Rouvray.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Bois_de_Boulogne",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f"),new MongoId("504d89f4fa9a958808000001")),
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
	"_id" => new MongoId("507bfe171d22b3954e000018"),
	"title"=>"bassin de la Villette",
	"content" =>"Le bassin de la Villette est le plus grand plan d'eau artificiel de Paris. Il a été mis en eaux le 2 décembre 1808. Situé dans le 19e arrondissement de la capitale, il relie le canal de l'Ourcq au canal Saint-Martin et constitue l'un des éléments du réseau des canaux parisiens.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://www.tourisme93.com/document.php?pagendx=1006",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f"),new MongoId("504d89f4fa9a958808000001")),
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
	"_id" => new MongoId("5059750efa9a954014000064"),
	"title"=>"jardin d'Acclimatation",
	"content" =>"Parc de loisirs et d'agrément s'étendant sur 19 hectares.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Jardin_d'acclimatation_(Paris)/",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f"),new MongoId("504d89f4fa9a958808000001")),
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
		'transportation'=>"
En voiture
Le parking Vinci du Palais des Congrès et le Jardin d'Acclimatation vous proposent 50% de réduction sur le stationnement et sur le trajet en Petit Train.
Avec le petit train
Au départ de la Porte Maillot, le Petit Train vous conduit à travers bois jusqu'à l'entrée principale du Jardin d'Acclimatation.
En métro
Station les Sablons, sortie 2, puis prendre la rue d'Orléans, l'entrée du Jardin d'Acclimatation est à 150m.
En bus
Le Jardin d'Acclimatation est desservi par 6 bus : 43 - 73 - 82 - PC - 174 - 244",
		'web'=>'http://www.jardindacclimatation.fr',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);

$records[] = array(
	"_id" => new MongoId("507bfe171d22b3954e000019"),
	"title"=>"jardin des Plantes",
	"content" =>"Jardin botanique ouvert au public, situé dans le Ve arrondissement de Paris, entre la mosquée de Paris, le campus de Jussieu et la Seine. Il appartient au Muséum national d'histoire naturelle et est, à ce titre, un campus.
Placé sous le patronage de Buffon jusqu'en 1788, il s'étend sur une superficie de 23,5 hectares.",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "http://fr.wikipedia.org/wiki/Jardin_des_plantes_de_Paris",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("50596cdafa9a95401400004f"),new MongoId("504d89f4fa9a958808000001")),
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
		'transportation'=>"M5, M10, Gare d'Austerlitz",
		'web'=>'http://www.jardindesplantes.net/',
	),		
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
);



$records[] = array(
	"_id" => new MongoId("507bfe171d22b3954e00001a"),
	"title"=>"tunnel des Halles",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("5056b7aafa9a95180b000000"),new MongoId("504d89f4fa9a958808000001")),
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

           
                   
$row1=0;	
$row2=0;	

foreach($records as $record){
	$res = $place->findOne(array('title'=>$record['title'],'status'=>1,'zone'=>1));
	if(empty($res)){
		$row1++;
		$place->save($record);
		echo "SAVED ".$record['title']." : ".$record['_id']."<br>";                    
	}else{
		if(!empty($record["_id"])){
			$row2++;
			$place->update(array("_id"=>$record["_id"]),$record);
			echo "UPDATED ".$record['title']." : ".$record['_id']."<br>";                    
		}
	}	
}

echo "<br>".$row1." records added.";
echo "<br>".$row2." records updated.";
       		
$place->ensureIndex(array("location"=>"2d"));
$place->ensureIndex(array("title"=>1,"content"=>1,"status"=>1,"zone"=>1));


//$place->update(array("_id"=>"50517fe1fa9a95040b000007"),array('$pull'=>array('yakCat'=>1)));

//$place->remove(array('title'=>new MongoRegex("/bois de Boulogne/i")));		

//$place->remove(array('status'=>10));

//$place->remove(array('filesourceId'=>new MongoId("507ff6e7fa9a95e80c000497")));


//$cursor = $place->find(array('filesourceId'=>new MongoId("507ff6e7fa9a95e80c000497")))->sort(array("creation"=>-1));
//var_dump(iterator_to_array($cursor));
/*
foreach ($cursor as $doc) {
    //echo $doc['creation']->sec;
	echo date('d-m-Y',$doc['creationDate']->sec) .'  '.	$doc['title']." yakcat :".$doc['yakCat'][0]." -- ".$doc['yakCat'][1]."<br>";
}*/

//$res = $place->update(array('filesourceId'=>new MongoId("507ff6e7fa9a95e80c000497")),array('$set'=>array('yakCat'=>array(new MongoId("507e5a9a1d22b30c44000068"),new MongoId("504d89f4fa9a958808000001")))),array("multiple" => true));
//$res = $place->update(array('filesourceId'=>new MongoId("507e74defa9a95f00c000027")),array('$set'=>array('yakCat'=>array(new MongoId("507e5a9a1d22b30c44000068"),new MongoId("504d89f4fa9a958808000001")))),array("multiple" => true));



//$place->update(array("zone"=>30,"filesourceId"=>new MongoId("507ff6e7fa9a95e80c000497")),array('$set'=>array('zone'=>11)),array('multiple'=>true));

/*
$cursor = $place->find(array('origin'=>new MongoRegex("/http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47")));		
//var_dump(iterator_to_array($cursor));

foreach ($cursor as $doc) {
    //echo $doc['creation']->sec;
	echo date('d-m-Y',$doc['creationDate']->sec) .'  '.	$doc['title']." yakcat :".$doc['yakCat'][0]." -- <br>";
}

$place->remove(array('origin'=>new MongoRegex("/http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47")));	
*/
?>