<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php

//"defaultPrintFlag" => 0, if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
// "defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$feed = $db->feed;

$records = array();


$records[] = array(
	"_id" => new MongoId("51002445fa9a953c0b000008"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Nice",	
	"name"=>"nicematin-nice",  // will be the file name
	"link"=>array("http://www.nicematin.com/taxonomy/term/394/rss"
					,"http://www.nicematin.com/taxonomy/term/61/rss"
					,"http://www.nicematin.com/derniere-minute/rss"),
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'address' => "",
						'outGoingLink' => "#YKLlink",
						'latitude'=>"",
						'longitude'=>"",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'',
						'place'=> '',
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
						'telephone'=>''
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("510022a0fa9a954c0b000031"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('510022a0fa9a954c0b00002f'), // 
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	



$records[] = array(
	"_id" => new MongoId("51002445fa9a953c0b000009"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Cannes",	
	"name"=>"nicematin-cannes",  // will be the file name
	"link"=>"http://www.nicematin.com/taxonomy/term/335/rss",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'address' => "",
						'outGoingLink' => "#YKLlink",
						'latitude'=>"",
						'longitude'=>"",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'',
						'place'=> '',
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
						'telephone'=>''
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("510022a0fa9a954c0b000031"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('510022a0fa9a954c0b000030'), // 
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51002445fa9a953c0b00000a"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Cagnes Sur Mer",	
	"name"=>"nicematin-cagnes-sur-mer",  // will be the file name
	"link"=>"http://www.nicematin.com/taxonomy/term/333/rss",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'address' => "",
						'outGoingLink' => "#YKLlink",
						'latitude'=>"",
						'longitude'=>"",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'',
						'place'=> '',
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
						'telephone'=>''
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("510022a0fa9a954c0b000031"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('510022a0fa9a954c0b000031'), // 
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
	
	
	
$records[] = array(
	"_id" => new MongoId("5100162bfa9a951c0b000000"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Antibe",	
	"name"=>"nicematin-antibe",  // will be the file name
	"link"=>"http://www.nicematin.com/taxonomy/term/310/rss",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'address' => "",
						'outGoingLink' => "#YKLlink",
						'latitude'=>"",
						'longitude'=>"",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'',
						'place'=> '',
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
						'telephone'=>''
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('51001523fa9a953c0b000000'), 
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
	
$records[] = array(
	"_id" => new MongoId("50ffb135fa9a95e00a000000"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Menton",	
	"name"=>"nicematin-menton",  // will be the file name
	"link"=>"http://www.nicematin.com/taxonomy/term/389/rss",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'address' => "",
						'outGoingLink' => "#YKLlink",
						'latitude'=>"",
						'longitude'=>"",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'',
						'place'=> '',
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
						'telephone'=>''
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50ff9f89fa9a95100b000000'), // MENTON
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	


$records[] = array(
	"_id" => new MongoId("50fd24f3fa9a955c0b00016b"),
	"XLconnector"=>"parser",
	"humanName"=>"CCIMP",	
	"name"=>"CCIMP",
	"link"=>"http://opendata.regionpaca.fr/donnees/detail/signataires-de-la-charte-esprit-client-de-la-ccimp.html",
	"fileSource"=>"./input/CCIMP-A-CLIENTS-01-2013_small.csv",
	"rootElement"=>"",
	"lineToBegin"=>"3",
	"parsingTemplate"=>array(
						'title' => "#YKL2 est signataire de la charte Esprit Client 2013 de la CCIMP",
						'content' => "",
						'address' => "#YKL3 #YKL4 #YKL5 #YKL6 #YKL7, FRANCE",
						'outGoingLink' => "http://www.espritclient.ccimp.com/",
						'latitude'=>"#YKL15",
						'longitude'=>"#YKL14",
						'thumb'=>'',
						'yakCats'=>'',
						'freeTag'=>'#YKL13',
						'place'=> '#YKL2',
						'eventDate' => '',
						'pubDate'=>'2013-01-01T00:00:00+0100',
						'telephone'=>'#YKL10'
					),
	"yakCatNameArray" => array('Commerce'),
	"yakCatId"=>array("50f025f7fa9a957c0c000048"),
 	"persistDays" => 365,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'), //PACA
	"yakType" => 3, // infos pratiques
	"feedType" => "CSV",
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 365,
	"zone" =>14,
);	

	
$records[] = array(
	"_id" => new MongoId("50f90ebc1d22b32548000118"),
	"XLconnector"=>"parser",
	"name"=>"MP2013",
	"humanName"=>"Marseille-Provence 2013",	
	"link"=>"http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=json&offset=0&limit=10",
	"yakCatNameArray" => array('Agenda','Culture'),
	"yakCatId"=>array(),
 	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'), // PACA
	"yakType" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 365,
	"zone" =>14,
);

$records[] = array(
	"_id" => new MongoId("50f53573fa9a95381100006a"),
	"XLconnector"=>"parser",
	"humanName"=>"parser",	
	"name"=>"Universal parser",
	"link"=>"http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=json&offset=0&limit=10",
	"rootElement"=>"rdf:Description",
	"parsingTemplate"=>array(
						'title' => 'name',
						'content' => 'description',
						'address' => 'event:location.place:address.address:name+event:location.place:address.address:streetAddress+event:location.place:address.address:postalCode+event:location.place:address.address:addressLocality',
						'outGoingLink' => '@attributes->rdf:about',	
					),
	"yakCatNameArray" => array('TEST'),
	"yakCatId"=>array(),
 	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'), //PAris
	"yakType" => 2,
	"feedType" => "JSON",
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 365,
	"zone" =>0,
);	




// CULTURE

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000045"),
	"XLconnector"=>"paris-bouge",
	"humanName"=>"parisbouge.com",	
	"name"=>"Paris Bouge",
	"link"=>"http://www.parisbouge.com/",
	"yakCatNameArray" => array('Actualités','Culture'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("504d89cffa9a957004000001")),
	"persistDays" => 90,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 50,
	"zone" =>1,
);		

	







// INFO ACTU

/*PACA*/
/*
//https://twitter.com/MuCEM_Officiel



http://www.laprovence.com/rss/Avignon-A-la-une.xml
http://www.laprovence.com/rss/Digne-les-bains-A-la-une.xml
http://www.laprovence.com/rss/Marseille-A-la-une.xml
http://www.laprovence.com/rss/Gap-A-la-une.xml
http://www.laprovence.com/rss/Aix-en-Provence-A-la-une.xml
http://www.laprovence.com/rss/Arles-A-la-une.xml
http://www.laprovence.com/rss/Aubagne-A-la-une.xml
http://www.laprovence.com/rss/Carpentras-A-la-une.xml
http://www.laprovence.com/rss/Cavaillon-A-la-une.xml
http://www.laprovence.com/rss/Istres-A-la-une.xml
http://www.laprovence.com/rss/Manosque-A-la-une.xml
http://www.laprovence.com/rss/Martigues-A-la-une.xml
http://www.laprovence.com/rss/Orange-A-la-une.xml
http://www.laprovence.com/rss/Salon-de-Provence-A-la-une.xml
*/



//http://www.laprovence.com/rss/rss_om.xml
//http://www.laprovence.com/rss/OM-Actualites-A-la-une.xml

$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000001"),
	"XLconnector"=>"laprovenceom",
	"humanName"=>"La Provence",	
	"name"=>"La Provence OM",
	"link"=>"http://www.laprovence.com",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("506479f54a53042191000000"),new MongoId("50647e2d4a53041f91040000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);

//http://www.laprovence.com/rss/Politique.xml
$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000002"),
	"XLconnector"=>"laprovencepolitique",
	"humanName"=>"La Provence",	
	"name"=>"La Provence Politique",
	"link"=>"http://www.laprovence.com",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("50efebbffa9a95b40c000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);

//http://www.laprovence.com/rss/Economie-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000003"),
	"XLconnector"=>"laprovenceeconomie",
	"humanName"=>"La Provence",	
	"name"=>"La Provence Eco",
	"link"=>"http://www.laprovence.com",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("50efebbffa9a95b40c000001")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);


//http://www.laprovence.com/rss/Sport-Region.xml
//http://www.laprovence.com/rss/Sports-en-direct.xml
$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000005"),
	"XLconnector"=>"laprovencesport",
	"humanName"=>"La Provence",	
	"name"=>"La Provence Sport",
	"link"=>"http://www.laprovence.com",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("506479f54a53042191000000")),
	"yakCatId"=>array(),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);



//http://www.laprovence.com/rss/Region-en-direct.xml
//http://www.laprovence.com/rss/Region.xml
$records[] = array(
	"_id" => new MongoId("50eef24afa9a954c0a000041"),
	"XLconnector"=>"laprovenceregion",
	"humanName"=>"La Provence",	
	"name"=>"La Provence Region",
	"link"=>"http://www.laprovence.com",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"yakCatId"=>array(),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	



/*BELGIQUE*/
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004b"),
	"XLconnector"=>"rtbf_namur",
	"humanName"=>"la RTBF",	
	"name"=>"RTBF Namur",
	"link"=>"http://http://www.rtbf.be",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"yakCatId"=>array(),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>5,
);	

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004c"),
	"XLconnector"=>"sudinfo_namur",
	"humanName"=>"Sud Info",	
	"name"=>"Sud Info",
	"link"=>"http://www.sudinfo.be",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>5,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004d"),
	"XLconnector"=>"rtbf_bruxelles",
	"humanName"=>"la RTBF",	
	"name"=>"RTBF Bruxelles",
	"link"=>"http://www.rtbf.be",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e9ce11d22b3944e00005a'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>4,
);	

/*MONTPELIER*/
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004e"),
	"XLconnector"=>"zone2",
	"humanName"=>"toutmontpellier.fr",	
	"name"=>"Tout Montpellier",
	"link"=>"http://www.toutmontpellier.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507eaca21d22b3954e0000e0'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>2,
);	


/*IDF*/
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004a"),
	"XLconnector"=>"france3-faitsdivers",
	"humanName"=>"france3.fr",	
	"name"=>"FR3 Faits Divers",
	"link"=>"http://france3.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 5,
	"zone" =>1,
);	



$records[] = array(
	"_id" => new MongoId("509b6150fa9a95a40b000000"),
	"XLconnector"=>"parisien75",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 75",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,// if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004f"),
	"XLconnector"=>"parisien77",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 77",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50811ceffa9a95280f000037'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>7,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000050"),
	"XLconnector"=>"parisien78",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 78",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000004'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>8,
);


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000051"),
	"XLconnector"=>"parisien91",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 91",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000003'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>9,
);			

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000052"),
	"XLconnector"=>"parisien92",
	"humanName"=>"leparisien.fr",
	"name"=>"Le Parisien 92",	
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000019'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>10,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000053"),
	"XLconnector"=>"parisien93",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 93",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000018'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>11,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000054"),
	"XLconnector"=>"parisien94",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 94",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000017'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>12,
);			

$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000002"),
	"XLconnector"=>"parisien95",
	"humanName"=>"leparisien.fr",	
	"name"=>"Le Parisien 95",
	"link"=>"http://www.leparisien.fr",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000016'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>13,
);

// IMMOBILIER
$records[] = array(
	"_id" => new MongoId("50ed3b42fa9a95040c000000"),
	"XLconnector"=>"PARIS_ATTITUDE",
	"humanName"=>"Paris attitude",	
	"name"=>"Paris Attitude",
	"link"=>"http://www.twitter.com/PARIS_ATTITUDE",
	"yakCatId"=>array(new MongoId("508fc6ebfa9a95680b000029"),new MongoId("50f01dcefa9a95bc0c00005f")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 2,// if not geolocalized, we ignore the news 
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 2,
	"daysBack" => 5,
	"zone" =>1,
);
$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000003"),
	"XLconnector"=>"PARIS_ATT_VENTE",
	"humanName"=>"Paris attitude Vente",	
	"name"=>"Paris Attitude Vente",
	"link"=>"http://www.twitter.com/PARIS_ATT_VENTE",
	"yakCatId"=>array(new MongoId("508fc6ebfa9a95680b000029")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 2,// if not geolocalized, we ignore the news 
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000055"),
	"XLconnector"=>"century_75014",
	"humanName"=>"Century21 14ème",	
	"name"=>"Century21 75014",
	"link"=>"http://www.twitter.com/century_75014",
	"yakCatId"=>array(new MongoId("508fc6ebfa9a95680b000029")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000056"),
	"XLconnector"=>"rebe100x",
	"humanName"=>"rebe100x",	
	"name"=>"Tweeter test @rebe100x",
	"link"=>"http://www.twitter.com/rebe100x",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 5,
	"zone" =>1,
);					
				
$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $feed->findOne(array('name'=>$record['name'],'humanName'=>$record['humanName'],"XLconnector"=>$record['XLconnector']));
	if(empty($res)){
		$row1++;
		$feed->save($record);
		echo $record['humanName']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$feed->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    

					
$feed->ensureIndex(array("XLconnector"=>1,"login"=>1,'status'=>1));
?>