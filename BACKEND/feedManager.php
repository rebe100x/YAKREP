<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php

//"defaultPrintFlag" => 0, if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
// "defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
// "defaultPrintFlag" => 2,// do not perform a geoloc and locate on the default location of the feed
 
 
// name : nameof the file
// humanName : Unique and for the front and the autocomplete
// XLconnectoer : for the mapping to XL 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$feed = $db->feed;

$records = array();


/*********************************************************************************************************************/
/*VAR MATIN*/
/*********************************************************************************************************************/

// var http://www.varmatin.com/taxonomy/term/98723/rss
// var http://www.varmatin.com/derniere-minute/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000006"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Région",	
	"name"=>"varmatin-region",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/98723/rss"
					,"http://www.varmatin.com/derniere-minute/rss"),
	"link"=>"http://www.varmatin.com",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('51095062fa9a95280c000003'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	

// hyeres http://www.varmatin.com/taxonomy/term/555/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000007"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Hyères",	
	"name"=>"varmatin-hyeres",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/555/rss"),
	"link"=>"http://www.varmatin.com/actu/hyeres",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf21d22b3cb6d01c6cc'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// Draguignan http://www.varmatin.com/taxonomy/term/536/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000008"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Draguignan",	
	"name"=>"varmatin-draguignan",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/536/rss"),
	"link"=>"http://www.varmatin.com/actu/draguignan",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf11d22b3cb6d01c699'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// Brignoles http://www.varmatin.com/taxonomy/term/509/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000009"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Brignoles",	
	"name"=>"varmatin-brignoles",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/509/rss"),
	"link"=>"http://www.varmatin.com/actu/brignoles",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf01d22b3cb6d01c654'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// fréjus http://www.varmatin.com/taxonomy/term/547/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000a"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Fréjus",	
	"name"=>"varmatin-frejus",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/547/rss"),
	"link"=>"http://www.varmatin.com/actu/frejus",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf11d22b3cb6d01c6ba'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// gapeau http://www.varmatin.com/taxonomy/term/1091/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000b"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Gapeau",	
	"name"=>"varmatin-gapeau",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/1091/rss"),
	"link"=>"http://www.varmatin.com/actu/gapeau",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf21d22b3cb6d01c6cc'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// la seyne sur mer http://www.varmatin.com/taxonomy/term/612/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000c"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Seyne sur Mer",	
	"name"=>"varmatin-seyne-sur-mer",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/612/rss"),
	"link"=>"http://www.varmatin.com/actu/la-seyne-sur-mer",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf31d22b3cb6d01c708'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// st tropez http://www.varmatin.com/taxonomy/term/605/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000d"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - St Tropez",	
	"name"=>"varmatin-st-tropez",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/605/rss"),
	"link"=>"http://www.varmatin.com/actu/saint-tropez",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf71d22b3cb6d01c861'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	

// toulon http://www.varmatin.com/taxonomy/term/623/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000e"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Toulon",	
	"name"=>"varmatin-toulon",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/taxonomy/term/623/rss"),
	"link"=>"http://www.varmatin.com/actu/toulon",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf71d22b3cb6d01c891'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// rugby http://www.varmatin.com/rct/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c00000f"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Rugby",	
	"name"=>"varmatin-rugby",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/rct/rss"),
	"link"=>"http://www.varmatin.com/sports/rugby",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Sport','Rugby'),
	"yakCatId"=>array("504d89c5fa9a957004000000","506479f54a53042191000000","50647e2d4a53041f91050000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('51095062fa9a95280c000003'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// basket http://www.varmatin.com/basket/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000010"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Basket",	
	"name"=>"varmatin-basket",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/basket/rss"),
	"link"=>"http://www.varmatin.com/sports/basket",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Sport','Basket'),
	"yakCatId"=>array("504d89c5fa9a957004000000","506479f54a53042191000000","51094f75fa9a95280c000001"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('51095062fa9a95280c000003'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur","yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	
// handball http://www.varmatin.com/handball/rss
$records[] = array(
	"_id" => new MongoId("510951b4fa9a95280c000011"),
	"XLconnector"=>"parser",
	"humanName"=>"Var Matin - Handball",	
	"name"=>"varmatin-handball",  // will be the file name
	"linkSource"=>array("http://www.varmatin.com/handball/rss"),
	"link"=>"http://www.varmatin.com/sports/handball",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Sport','Handball'),
	"yakCatId"=>array("504d89c5fa9a957004000000","506479f54a53042191000000","51094f75fa9a95280c000000"),
 	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('51095062fa9a95280c000003'), 
	"defaultPlaceSearchName" => "Var, Provence-Alpes-Côte-d'Azur",
	"yakType" => 1, // actu
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 2,
	"zone" =>14,
);	

/*********************************************************************************************************************/
/*NICE MATIN*/
/*********************************************************************************************************************/
$records[] = array(
	"_id" => new MongoId("51002445fa9a953c0b000008"),
	"XLconnector"=>"parser",
	"humanName"=>"Nice Matin - Nice",	
	"name"=>"nicematin-nice",  // will be the file name
	"linkSource"=>array("http://www.nicematin.com/taxonomy/term/394/rss"
					,"http://www.nicematin.com/taxonomy/term/61/rss"
					,"http://www.nicematin.com/derniere-minute/rss"),
	"link"=>"http://www.nicematin.com",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
						
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
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
	"linkSource"=>array("http://www.nicematin.com/taxonomy/term/335/rss"),
	"link"=>"http://www.nicematin.com/actu/cannes",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
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
	"linkSource"=>array("http://www.nicematin.com/taxonomy/term/333/rss"),
	"link"=>"http://www.nicematin.com/actu/cagnes-sur-mer",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array("504d89c5fa9a957004000000"),
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
	"humanName"=>"Nice Matin - Antibes",	
	"name"=>"nicematin-antibes",  // will be the file name
	"linkSource"=>array("http://www.nicematin.com/taxonomy/term/310/rss"),
	"link"=>"http://www.nicematin.com/actu/antibes",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
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
	"linkSource"=>array("http://www.nicematin.com/taxonomy/term/389/rss"),
	"link"=>"http://www.nicematin.com/actu/menton",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
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
	"linkSource"=>array(""),
	"link"=>"http://opendata.regionpaca.fr/donnees/detail/signataires-de-la-charte-esprit-client-de-la-ccimp.html",
	"fileSource"=>"./input/CCIMP-A-CLIENTS-01-2013.csv",
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
	"licence"=>"Open",
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

// http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-evenements/

$records[] = array(
	"_id" => new MongoId("510bd0a7fa9a95cc0c000007"),
	"XLconnector"=>"parser",
	"humanName"=>"Hôtels des Bouches-du-Rhône",	
	"name"=>"hotels-bouches-du-rhone",
	"linkSource"=>array(""),
	"link"=>"http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-hotels/",
	"fileSource"=>"./input/hotels-bouches-du-rhone.csv",
	"rootElement"=>"",
	"lineToBegin"=>"1",
	"parsingTemplate"=>array(
						'title' => "#YKL0",
						'content' => "#YKL2 #YKL3 <br/> #YKL4",
						'address' => "#YKL6 #YKL7 #YKL8 #YKL9 #YKL10 #YKL11 #YKL12 #YKL13 #YKL14, FRANCE",
						'outGoingLink' => "#YKL20",
						'latitude'=>"#YKL22",
						'longitude'=>"#YKL21",
						'thumb'=>'',
						'yakCats'=>'510bcee3fa9a95cc0c000003',
						'freeTag'=>'#YKL2',
						'place'=> "#YKL0",
						'eventDate' => '',
						'pubDate'=>'2013-01-01T00:00:00+0100',
						'telephone'=>'#YKL18',
						'mail'=>'#YKL19',
						'web'=>'#YKL20',
						
					),
	"licence"=>"Open",				
	"yakCatNameArray" => array('Hôtel'),
	"yakCatId"=>array("510bcee3fa9a95cc0c000003"),
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
	"_id" => new MongoId("510bcd4dfa9a95cc0c000001"),
	"XLconnector"=>"parser",
	"humanName"=>"Restaurants gastronomiques des Bouches-du-Rhône",	
	"name"=>"restaurants-gastronomiques-bouches-du-rhone",
	"linkSource"=>array(""),
	"link"=>"http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-restaurants-gastronomiques/",
	"fileSource"=>"./input/restaurants-gastronomiques-bouches-du-rhone.csv",
	"rootElement"=>"",
	"lineToBegin"=>"1",
	"parsingTemplate"=>array(
						'title' => "#YKL0",
						'content' => "Type de cuisine: #YKL3",
						'address' => "#YKL5 #YKL6 #YKL7 #YKL8 #YKL9 #YKL10 #YKL11 #YKL12, FRANCE",
						'outGoingLink' => "#YKL18",
						'latitude'=>"#YKL20",
						'longitude'=>"#YKL19",
						'thumb'=>'',
						'yakCats'=>'50896423fa9a954c01000000',
						'freeTag'=>'#YKL3',
						'place'=> "#YKL0",
						'eventDate' => '',
						'pubDate'=>'2013-01-01T00:00:00+0100',
						'telephone'=>'#YKL16',
						'mail'=>'#YKL17',
						'web'=>'#YKL18',
						
					),
	"licence"=>"Open",				
	"yakCatNameArray" => array('Restaurants'),
	"yakCatId"=>array("50896423fa9a954c01000000"),
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
	"_id" => new MongoId("510b7b4bfa9a95480a000007"),
	"XLconnector"=>"parser",
	"humanName"=>"Restaurants des Bouches-du-Rhône",	
	"name"=>"restaurants-bouches-du-rhone",
	"linkSource"=>array(""),
	"link"=>"http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-restaurants/",
	"fileSource"=>"./input/restaurants-bouches-du-rhone.csv",
	"rootElement"=>"",
	"lineToBegin"=>"1",
	"parsingTemplate"=>array(
						'title' => "#YKL0",
						'content' => "Type de cuisine: #YKL3",
						'address' => "#YKL5 #YKL6 #YKL7 #YKL8 #YKL9 #YKL10 #YKL11 #YKL12, FRANCE",
						'outGoingLink' => "#YKL18",
						'latitude'=>"#YKL20",
						'longitude'=>"#YKL19",
						'thumb'=>'',
						'yakCats'=>'50896423fa9a954c01000000',
						'freeTag'=>'#YKL3',
						'place'=> "#YKL0",
						'eventDate' => '',
						'pubDate'=>'2013-01-01T00:00:00+0100',
						'telephone'=>'#YKL16',
						'mail'=>'#YKL17',
						'web'=>'#YKL18',
						
					),
	"licence"=>"Open",				
	"yakCatNameArray" => array('Restaurants'),
	"yakCatId"=>array("50896423fa9a954c01000000"),
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
	"_id" => new MongoId("510b743efa9a95080a000005"),
	"XLconnector"=>"parser",
	"humanName"=>"Loueurs de vélos à Marseille",	
	"name"=>"location-velo-marseille",
	"linkSource"=>array(""),
	"link"=>"http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-loueurs-de-velo/",
	"fileSource"=>"./input/locationdevelo.csv",
	"rootElement"=>"",
	"lineToBegin"=>"1",
	"parsingTemplate"=>array(
						'title' => "#YKL0",
						'content' => "",
						'address' => "#YKL4 #YKL6 #YKL7 #YKL8 #YKL10 #YKL11, FRANCE",
						'outGoingLink' => "http://www.espritclient.ccimp.com/",
						'latitude'=>"#YKL19",
						'longitude'=>"#YKL18",
						'thumb'=>'',
						'yakCats'=>'50c5e0c91d22b3bf38000fb0',
						'freeTag'=>'Vélos#LouerVélo#Cycles',
						'place'=> "#YKL0",
						'eventDate' => '',
						'pubDate'=>'2013-01-01T00:00:00+0100',
						'telephone'=>'#YKL15',
						'mail'=>'#YKL16',
						'web'=>'#YKL17',
						
					),
	"licence"=>"Open",				
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
	"linkSource"=>array("http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=json&offset=0&limit=10"),
	"link"=>"http://www.mp2013.fr",
	"licence"=>"Open",				
	"yakCatNameArray" => array('Agenda','Culture'),
	"yakCatId"=>array(),
 	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'), // PACA
	"yakType" => 2,
	"feedType" => "YAKMADE",
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 365,
	"zone" =>14,
);






// CULTURE

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000045"),
	"XLconnector"=>"parser",
	"humanName"=>"Paris Bouge",	
	"name"=>"paris-bouge",
	"linkSource"=>array("http://feeds.feedburner.com/parisbouge"),
	"link"=>"http://www.paris-bouge.fr",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",						
						
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Culture'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("504d89cffa9a957004000001")),
	"persistDays" => 90,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"feedType" => "RSS",
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

*/

// METEO http://api.meteorologic.net/api/api/rss_simple.php?id=4330&
// http://www.meteorologic.net/zone-rss.php?id=1945
$records[] = array(
	"_id" => new MongoId("51246fdafa9a95600b000003"),
	"XLconnector"=>"parser",
	"humanName"=>"Météo Marseille",	
	"name"=>"meteo-marseille",
	"linkSource"=>array("http://api.meteorologic.net/api/api/rss_simple.php?id=4330"),
	"link"=>"http://www.meteorologic.net/meteo-france/Marseille_4330.html",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'thumb'=>"http://batch.yakwala.fr/PROD/YAKREP/BACKEND/batchthumb/meteo_#YKLmeteoweather->pictos_matin.jpg",
						'outGoingLink' => "#YKLlink",
						'pubDate'=>"#YKLpubDate",
					),
	"thumbFlag"=>1,				
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Météo'),
	"yakCatId"=>array(new MongoId("51246d43fa9a95080b000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('510a3b1a1d22b31e68003d87'),
	"defaultPlaceSearchName" => "Marseille, France",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 2,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);


//http://www.laprovence.com/rss/rss_om.xml
//http://www.laprovence.com/rss/OM-Actualites-A-la-une.xml

$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000001"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence OM",	
	"name"=>"la-provence-om",
	"linkSource"=>array("http://www.laprovence.com/rss/OM-Actualites-A-la-une.xml"
				,"http://www.laprovence.com/rss/rss_om.xml"),
	"link"=>"http://www.laprovence.com/om-a-la-une",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'thumb'=>'#YKLenclosure->url',
						'outGoingLink' => "#YKLlink",
						'eventDate' => '',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Sport','Football'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("506479f54a53042191000000"),new MongoId("50647e2d4a53041f91040000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5101588dfa9a95000a000000'),
	"defaultPlaceSearchName" => "Stade Vélodrome, Marseille, France",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 2,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);

//http://www.laprovence.com/rss/Politique.xml
$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000002"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Politique",	
	"linkSource"=>array("http://www.laprovence.com/rss/Politique.xml"),
	"link"=>"http://www.laprovence.com/politique",
	"name"=>"la-provence-politique",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Politique'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("50efebbffa9a95b40c000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);

//http://www.laprovence.com/rss/Economie-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("50eef86efa9a955c0a000003"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Eco",	
	"name"=>"la-provence-economie",
	"linkSource"=>array("http://www.laprovence.com/rss/Economie-A-la-une.xml"
					,"http://www.laprovence.com/rss/Economie-en-direct.xml"),
	"link"=>"http://www.laprovence.com/economie-a-la-une",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Economie'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("50efebbffa9a95b40c000001")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1,
	"feedType" => "RSS",
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
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Sport",	
	"name"=>"la-provence-sport",
	"linkSource"=>array('http://www.laprovence.com/rss/Sport-Region.xml'
				,'http://www.laprovence.com/rss/Sports-en-direct.xml'),
	"link"=>"http://www.laprovence.com/Sports-en-direct",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités','Sport'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000"),new MongoId("506479f54a53042191000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1,
	"feedType" => "RSS",
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
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Région",	
	"name"=>"la-provence-region",
	"linkSource"=>array('http://www.laprovence.com/rss/Region-en-direct.xml'
				,'http://www.laprovence.com/rss/Region.xml'),
	"link"=>"http://www.laprovence.com/Region-en-direct",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50eefc29fa9a953c0a00001d'),
	"defaultPlaceSearchName" => "Provence-Alpes-Côte-d'Azur",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	






//http://www.laprovence.com/rss/Avignon-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("5108eb2f1d22b3356b00181c"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Avignon",	
	"name"=>"la-provence-avignon",
	"linkSource"=>array('http://www.laprovence.com/rss/Avignon-A-la-une.xml'),
	"link"=>"http://www.laprovence.com/Avignon",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5107cdf81d22b3cb6d01c8d3'),
	"defaultPlaceSearchName" => "Vaucluse",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

//http://www.laprovence.com/rss/Digne-les-bains-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("5109051a1d22b36a6d001084"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Digne-les-Bains",	
	"name"=>"la-provence-digne-le-bains",
	"linkSource"=>array("http://www.laprovence.com/rss/Digne-les-bains-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Digne-les-Bains",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c67f1d22b3cb6d001442"),
	"defaultPlaceSearchName" => "Alpes-de-Haute-Provence",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

//http://www.laprovence.com/rss/Gap-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c000006"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Gap",	
	"name"=>"la-provence-gap",
	"linkSource"=>array("http://www.laprovence.com/rss/Gap-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Gap",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c68a1d22b3cb6d0017e7"),
	"defaultPlaceSearchName" => "Hautes-Alpes",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

//http://www.laprovence.com/rss/Aix-en-Provence-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c000007"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Aix",	
	"name"=>"la-provence-aix",
	"linkSource"=>array("http://www.laprovence.com/rss/Aix-en-Provence-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Aix-en-Provence",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7241d22b3cb6d003b58"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	
//http://www.laprovence.com/rss/Arles-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c000008"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Arles",	
	"name"=>"la-provence-arles",
	"linkSource"=>array("http://www.laprovence.com/rss/Arles-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Arles",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7241d22b3cb6d003b67"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

//http://www.laprovence.com/rss/Aubagne-A-la-une.xml
$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c000009"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Aubagne",	
	"name"=>"la-provence-aubagne",
	"linkSource"=>array("http://www.laprovence.com/rss/Aubagne-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Aubagne",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7241d22b3cb6d003b6a"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	


$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000a"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Carpentras",	
	"name"=>"la-provence-carpentras",
	"linkSource"=>array("http://www.laprovence.com/rss/Carpentras-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Carpentras",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107cdf91d22b3cb6d01c921"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000b"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Cavaillon",	
	"name"=>"la-provence-cavaillon",
	"linkSource"=>array("http://www.laprovence.com/rss/Cavaillon-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Cavaillon",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107cdf91d22b3cb6d01c92d"),
	"defaultPlaceSearchName" => "Vaucluse",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000c"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Istres",	
	"name"=>"la-provence-istres",
	"linkSource"=>array("http://www.laprovence.com/rss/Istres-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Istres",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7261d22b3cb6d003bfd"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000d"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Manosque",	
	"name"=>"la-provence-manosque",
	"linkSource"=>array("http://www.laprovence.com/rss/Manosque-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Manosque",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c6821d22b3cb6d00150b"),
	"defaultPlaceSearchName" => "Alpes-de-Haute-Provence",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000e"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Martigues",	
	"name"=>"la-provence-martigues",
	"linkSource"=>array("http://www.laprovence.com/rss/Martigues-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Martigues",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7281d22b3cb6d003c57"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c00000f"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Orange",	
	"name"=>"la-provence-orange",
	"linkSource"=>array("http://www.laprovence.com/rss/Orange-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Orange",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107cdfd1d22b3cb6d01ca1a"),
	"defaultPlaceSearchName" => "Vaucluse",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	

$records[] = array(
	"_id" => new MongoId("51090c27fa9a95480c000010"),
	"XLconnector"=>"parser",
	"humanName"=>"La Provence Salon-de-Provence",	
	"name"=>"la-provence-salon-de-provence",
	"linkSource"=>array("http://www.laprovence.com/rss/Salon-de-Provence-A-la-une.xml"),
	"link"=>"http://www.laprovence.com/Salon-de-Provence",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array("Actualités"),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId("5107c7291d22b3cb6d003cd8"),
	"defaultPlaceSearchName" => "Bouches-du-Rhône",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>14,
);	


/**********/
/*BELGIQUE*/
/*********/
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004b"),
	"XLconnector"=>"parser",
	"humanName"=>"la RTBF Namur",	
	"name"=>"RTBF-namur",
	"linkSource"=>array("http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions-namur.xml",
					"http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions-liege.xml",
					"http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions-hainaut.xml",
					"http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions- luxembourg.xml",
					"http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions-brabant- wallon.xml"),
	"link"=>"http://www.rtbf.be/info/regions/namur",				
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"feedType" => "RSS",
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>5,
);	

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004c"),
	"XLconnector"=>"parser",
	"humanName"=>"Sud Info",	
	"name"=>"sud-info-namur",
	"linkSource"=>array("http://www.sudinfo.be/feed/Régions/Tournai",
					"http://www.sudinfo.be/feed/Régions/Mons",
					"http://www.sudinfo.be/feed/Régions/Mouscron",
					"http://www.sudinfo.be/feed/Régions/Sambre-Meuse",
					"http://www.sudinfo.be/feed/Régions/Centre",
					"http://www.sudinfo.be/feed/Régions/Charleroi",
					"http://www.sudinfo.be/feed/Régions/Namur",
					"http://www.sudinfo.be/feed/Régions/Liège",
					"http://www.sudinfo.be/feed/Régions/Brabant%20wallon",
					"http://www.sudinfo.be/feed/Régions/Verviers",
					"http://www.sudinfo.be/feed/Régions/Huy-Waremme",
					"http://www.sudinfo.be/feed/Régions/Luxembourg"),
	"link"=>"http://www.sudinfo.be/",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLmedia:content->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>5,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004d"),
	"XLconnector"=>"parser",
	"humanName"=>"la RTBF Bruxelles",	
	"name"=>"RTBF-bruxelles",
	"linkSource"=>array("http://rss.rtbf.be/article/rss/highlight_rtbfinfo_regions-bruxelles.xml"),
	"link"=>"http://www.rtbf.be/info/regions/bruxelles",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>'#YKLenclosure->url',
						'pubDate'=>'#YKLpubDate',
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('507e9ce11d22b3944e00005a'),
	"defaultPlaceSearchName" => "",
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
	"XLconnector"=>"parser",
	"humanName"=>"Tout Montpellier",	
	"name"=>"toutmontpellier",
	"linkSource"=>array("http://www.toutmontpellier.fr/rss.xml"),
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLimage.url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507eaca21d22b3954e0000e0'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 10,
	"zone" =>2,
);	


/*IDF*/
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004a"),
	"XLconnector"=>"france3-faitsdivers",
	"humanName"=>"FR3 Faits Divers",	
	"name"=>"france3-faits-divers",
	"linkSource"=>array("http://france3.fr"),
	"licence"=>"Reserved",
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 5,
	"zone" =>1,
);	



$records[] = array(
	"_id" => new MongoId("509b6150fa9a95a40b000000"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 75",	
	"name"=>"leparisien75",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/paris-75.xml"),
	"link"=>"http://www.leparisien.fr/accueil/paris-75.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 0,// if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004f"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 77",	
	"name"=>"leparisien77",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/seine-et-marne-77.xml"),
	"link"=>"http://www.leparisien.fr/accueil/seine-et-marne-77.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50811ceffa9a95280f000037'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>7,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000050"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 78",	
	"name"=>"leparisien78",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/yvelines-78.xml"),
	"link"=>"http://www.leparisien.fr/accueil/yvelines-78.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000004'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>8,
);


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000051"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 91",	
	"name"=>"leparisien91",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/essonne-91.xml"),
	"link"=>"http://www.leparisien.fr/accueil/essonne-91.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000003'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>9,
);			

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000052"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 92",
	"name"=>"leparisien92",	
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/hauts-de-seine-92.xml"),
	"link"=>"http://www.leparisien.fr/accueil/hauts-de-seine-92.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000019'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>10,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000053"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 93",	
	"name"=>"leparisien93",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/seine-saint-denis-93.xml"),
	"link"=>"http://www.leparisien.fr/accueil/seine-saint-denis-93.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000018'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>11,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000054"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 94",	
	"name"=>"leparisien94",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/val-de-marne-94.xml"),
	"link"=>"http://www.leparisien.fr/accueil/val-de-marne-94.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000017'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>12,
);			

$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000002"),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 95",	
	"name"=>"leparisien95",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/val-d-oise-95.xml"),
	"link"=>"http://www.leparisien.fr/accueil/val-d-oise-95.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000016'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>13,
);
/*
http://videos.leparisien.fr/channel/iLyROoaftilW.html?format=rss

$records[] = array(
	//"_id" => new MongoId(""),
	"XLconnector"=>"parser",
	"humanName"=>"Le Parisien 95",	
	"name"=>"leparisien95",
	"linkSource"=>array("http://rss.leparisien.fr/leparisien/rss/val-d-oise-95.xml"),
	"link"=>"http://www.leparisien.fr/accueil/oise-60.php",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'content' => "#YKLdescription",
						'outGoingLink' => "#YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLpubDate",
					),
	"yakCatNameArray" => array('Actualités'),
	"yakCatId"=>array(new MongoId("504d89c5fa9a957004000000")),
	"persistDays" => 1,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000016'),
	"defaultPlaceSearchName" => "",
	"yakType" => 1,
	"defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>13,
);
*/
// IMMOBILIER
$records[] = array(
	"_id" => new MongoId("50ed3b42fa9a95040c000000"),
	"XLconnector"=>"parser",
	"humanName"=>"Paris attitude",	
	"name"=>"PARIS_ATTITUDE",
	"linkSource"=>array("http://batch.yakwala.fr/PROD/YAKREP/BATCH/twitterFeedBuilder.php?screen_name=PARIS_ATTITUDE"),
	"link"=>"http://www.twitter.com/PARIS_ATTITUDE",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'outGoingLink' => "YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLcreated_at",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Immobilier'),
	"yakCatId"=>array(new MongoId("508fc6ebfa9a95680b000029"),new MongoId("50f01dcefa9a95bc0c00005f")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,// if not geolocalized, we ignore the news 
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"daysBack" => 5,
	"zone" =>1,
);
$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000003"),
	"XLconnector"=>"parser",
	"humanName"=>"Paris attitude Vente",	
	"name"=>"PARIS_ATT_VENTE",
	"linkSource"=>array("http://batch.yakwala.fr/PROD/YAKREP/BATCH/twitterFeedBuilder.php?screen_name=PARIS_ATT_VENTE"),
	"link"=>"http://www.twitter.com/PARIS_ATT_VENTE",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'outGoingLink' => "YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLcreated_at",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Immobilier'),	
	"yakCatId"=>array(new MongoId("508fc6ebfa9a95680b000029")),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 1,
	"defaultPrintFlag" => 0,// if not geolocalized, we ignore the news 
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000055"),
	"XLconnector"=>"parser",
	"humanName"=>"Century21 14ème",	
	"name"=>"century_75014",
	"linkSource"=>array("http://batch.yakwala.fr/PROD/YAKREP/BATCH/twitterFeedBuilder.php?screen_name=century_75014"),
	"link"=>"http://www.twitter.com/century_75014",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'outGoingLink' => "YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLcreated_at",
					),
	"licence"=>"Reserved",
	"yakCatNameArray" => array('Immobilier'),
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
	"XLconnector"=>"parser",
	"humanName"=>"rebe100x",	
	"name"=>"rebe100x",
	"linkSource"=>array("http://batch.yakwala.fr/PROD/YAKREP/BATCH/twitterFeedBuilder.php?screen_name=rebe100x"),
	"link"=>"http://www.twitter.com/rebe100x",
	"feedType" => "RSS",
	"fileSource"=>"",
	"rootElement"=>"/rss/channel/item",
	"lineToBegin"=>"0",
	"parsingTemplate"=>array(
						'title' => "#YKLtitle",
						'outGoingLink' => "YKLlink",
						'thumb'=>"#YKLenclosure->url",
						'pubDate'=>"#YKLcreated_at",
					),
	"licence"=>"Open",
	"yakCatNameArray" => array('Test'),
	"yakCatId"=>array(new MongoId("")),
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