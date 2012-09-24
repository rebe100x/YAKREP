<?php

require_once("library.php");

 class Info
 {
 	// name of the info
 	public $title;
 	
 	// some text to describe the info
 	public $content;
 	
 	// a local link to a picture of the info
 	public $thumb;

 	// where did we get this info
 	public $origin;

 	// 1 - public / 2 - privé for the api ( all open data is public )
 	public $access;

 	// copy the licence of the file you used
 	public $licence;
 	
 	public $outGoingLink;
 	
 	// the importance of the info 0->100
 	public $heat; 
 	
 	// flags if need to be printed on the map / fils info
 	public $print;
 	
 	// 1 actu, 2 agenda, 3 promo, 4 conversation
 	public $yakType;	
 	
	//Mongo ID idyakCat
	public $yakCat;
	
	// [{ enfants:0/1 }{ handicapés:0/1 }{ personnes agées:0/1 }{ couvert, intérieur:0/1 }{ gay friendly:0/1 }{ gratuit:0/1 }{ animaux:0/1 }]
 	public $yakTag;
	
	public $freeTag;
	
	// publication date in the feed
	public $pubDate;
	
	public $creationDate;

 	public $lastModifDate;
 	
 	// date max de print sur le front
	public $dateEndPrint;
	
	public $location;
	
	// flag for the workflow : 1: OK , 10 alert gmap not found
	public $status;
	
	// who created the info ( 0 for a batch )
 	public $user;
 	
	// used to speed up print by server : 1 Paris , 2, Mtplr, 3 Eghézéee , 4 Other
 	public $zone;
 	
 	// object : only if different from the place contact ( ex: for an expo the museaum and the expo do not have the same contact )
	public $contact;
	
	// debug field : the location string found by the semantic factory ( the string sent to gmap, used only for the rss parsing )
	public $address;
	
	public $placeId;
 	

 	function __construct() {
 		$this->title = '';
 		$this->content = '';
 		$this->thumb = '';
 		$this->origin = '';
 		$this->access = 1;
 		$this->licence = '';
 		$this->outGoingLink = '';
 		$this->heat = 80;
 		$this->print = 0;
 		$this->yakType = 0;
 		$this->yakCat = array('');
 		$this->yakTag = array (
			"enfants" => "0",
			"handicapés" => "0",
			"personnes agées" => "0",
			"couvert, intérieur" => "0",
			"gay friendly" => "0",
			"gratuit" => "0",
			"animaux" => "0",
		);
		$this->yakCat = array('');
 		$this->freeTag = '';
 		$this->pubDate = '';
 		$this->creationDate = time();
 		$this->lastModifDate = time();
 		$this->dateEndPrint = '';
 		$this->address = array (
			"street" => "",
			"zipcode" => "",
			"city" => "",
			"country" => "",
		);
 		$this->location = array (
			"lat" => "",
			"lng" => "",
		);
		$this->contact = array (
			"tel" => "",
			"mobile" => "",
			"mail" => "",
			"transportation" => "",
			"web" => "",
			"opening" => "",
			"closing" => "",
			"special opening" => "",
		);

		$this->status = 0;
		$this->user = 0;
		$this->zone = 1;
		$this->placeId = '';
 	}

 	function saveToMongoDB() {
 		$m = new Mongo();
		$db = $m->selectDB("yakwala");
		$info = $db->info;

		$record = array(
			"title"			=>	$this->title,
			"content" 		=>	$this->content,
			"thumb" 		=>	$this->thumb,
			"origin"		=>	$this->origin,	
			"access"		=>	$this->access,
			"licence"		=>	$this->licence,
			"outGoingLink"	=>	$this->outGoingLink,
			"heat"			=>	$this->heat,
			"print"			=>	$this->print,
			"yakType"		=>	$this->yakType,
			"yakCat" 		=>	$this->yakCat,
			"freeTag"		=>	$this->freeTag,
			"pubDate"		=>	$this->pubDate,
			"creationDate" 	=>	new MongoDate(gmmktime()),
			"lastModifDate" =>	new MongoDate(gmmktime()),
			"dateEndPrint"	=> 	$this->dateEndPrint,
			"location" 		=>	$this->location,
			"address" 		=>	$this->address,
			"contact"		=>	$this->contact,
			"status" 		=>	$this->status,
			"user"			=> 	$this->user, 
			"zone"			=> 	$this->zone,
			"placeid"		=>	$this->placeid,
		);	

		$rangeQuery = array('title' => $this->title, 'address' => $this->address);

		$cursor = $place->find($rangeQuery);
		foreach ($cursor as $doublon) {
    		//TODO : gérer la mise à jour des doublons
    		//var_dump($doublon);
    		return 1;
		}

		$info->save($record);
		return $record['_id'];
 	}

 	function getLocation($query, $debug)
 	{
 		$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($query))),'PHP', $debug);

 		if ($loc != 0)
 		{
 			$this->location["lat"] = $loc["location"][0];
 			$this->location["lng"] = $loc["location"][1];
 			return true;
 		}
 		return false;
 	}

 	function setCatActu()
 	{
 		$this->yakCat[] = new MongoId("504d89c5fa9a957004000000");
 	}
 	
	function setCatCulture()
 	{
 		$this->yakCat[] = new MongoId("504d89cffa9a957004000001");
 	}

 	function setCatGeoloc()
 	{
 		$this->yakCat[] = new MongoId("504d89f4fa9a958808000001");
 	}

 	function setCatEducation()
 	{
 		$this->yakCat[] = new MongoId("504dbb06fa9a95680b000211");
 	}
 	
 	function setCatEcole()
 	{
 		$this->yakCat[] = new MongoId("5056b89bfa9a95180b00000111");
 	}
 	
 	function setCatElementaire()
 	{
 		$this->yakCat[] = new MongoId("5056bae5fa9a95200b000001");
 	}
 	
 	function setCatMaternelle()
 	{
 		$this->yakCat[] = new MongoId("5056baddfa9a95200b000000");
 	}

 	function setCatTheatre()
 	{
 		$this->yakCat[] = new MongoId("504df6b1fa9a957c0b000004");
 	}
 	
 	function setCatExpo()
 	{
 		$this->yakCat[] = new MongoId("504df70ffa9a957c0b000006");
 	}
 	
 	function setCatCinema()
 	{
 		$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}
 	
 	function setZoneParis()
 	{
 		$this->zone = 1;
 	}

 	function setZoneMontpellier()
 	{
 		$this->zone = 2;
 	}

 	function setZoneEghezee()
 	{
 		$this->zone = 3;
 	}

 	function setZoneOther()
 	{
 		$this->zone = 4;
 	}
 	
 }