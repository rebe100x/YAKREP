<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$feed = $db->feed;

$records = array();
	
// PARSER
$records[] = array(
	"_id" => new MongoId("50f53573fa9a95381100006a"),
	"name"=>"parser",
	"humanName"=>"parser",	
	"link"=>"http://test.com",
	"yakCatNameArray" => array('TEST'),
	"yakCatId"=>array(),
 	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 2,
	"feedType" => "RDF",
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);	



// OPENDATA
$records[] = array(
	"_id" => new MongoId("50c5e1171d22b31544001273"),
	"name"=>"test",
	"humanName"=>"test",	
	"link"=>"http://data.visitprovence.com/les-donnees/fiche-donnee/donnees/liste-des-loueurs-de-velo/",
	"yakCatNameArray" => array('SPORT','Cyclisme'),
	"yakCatId"=>array(),
	"persistDays" => 180,
	"defaultPlaceId" => new MongoId('50c5e3311d22b3db2c000959'),
	"yakType" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
	"zone" =>1,
);	

// CULTURE

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000045"),
	"name"=>"paris-bouge",
	"humanName"=>"parisbouge.com",	
	"link"=>"http://www.parisbouge.com/",
	"yakCatNameArray" => array('ACTUALITES','Culture','Agenda'),
	"yakCatId"=>array(),
	"persistDays" => 180,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"yakType" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
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
	"name"=>"laprovenceom",
	"humanName"=>"La Provence",	
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
	"name"=>"laprovencepolitique",
	"humanName"=>"La Provence",	
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
	"name"=>"laprovenceeconomie",
	"humanName"=>"La Provence",	
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
	"name"=>"laprovencesport",
	"humanName"=>"La Provence",	
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
	"name"=>"laprovenceregion",
	"humanName"=>"La Provence",	
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
	"name"=>"rtbf_namur",
	"humanName"=>"la RTBF",	
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
	"name"=>"sudinfo_namur",
	"humanName"=>"Sud Info",	
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
	"name"=>"rtbf_bruxelles",
	"humanName"=>"la RTBF",	
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
	"name"=>"zone2",
	"humanName"=>"toutmontpellier.fr",	
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
	"name"=>"france3-faitsdivers",
	"humanName"=>"france3.fr",	
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
	"name"=>"parisien75",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien77",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien78",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien91",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien92",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien93",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien94",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"parisien95",
	"humanName"=>"leparisien.fr",	
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
	"name"=>"PARIS_ATTITUDE",
	"humanName"=>"Paris attitude",	
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
	"name"=>"PARIS_ATT_VENTE",
	"humanName"=>"Paris attitude Vente",	
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
	"name"=>"century_75014",
	"humanName"=>"Century21 14ème",	
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
	"name"=>"rebe100x",
	"humanName"=>"rebe100x",	
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
	$res = $feed->findOne(array('name'=>$record['name']));
	if(empty($res)){
		$row1++;
		$feed->save($record);
		echo $record['name']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$feed->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    

					
$feed->ensureIndex(array("name"=>1,"login"=>1,'status'=>1));
?>