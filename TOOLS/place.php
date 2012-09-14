<?php

require_once("../LIB/library.php");

 class Place
 {
 	// name of the place ( can be a building, an area, a street ... )
 	public $title;
 	
 	// some text to describe the place
 	public $content;
 	
 	// a local link to a picture of the place
 	public $thumb;

 	// where did we get this info
 	public $origin;

 	// 1 - public / 2 - privé for the api ( all open data is public )
 	public $access;

 	// copy the licence of the file you used
 	public $licence;

 	// [{ enfants:0/1 }{ handicapés:0/1 }{ personnes agées:0/1 }{ couvert, intérieur:0/1 }{ gay friendly:0/1 }{ gratuit:0/1 }{ animaux:0/1 }]
 	public $yakTag;

 	//Mongo ID idyakCat
	public $yakCat;

	public $freeTag;

 	public $creationDate;

 	public $lastModifDate;

 	// flag for the workflow : 1 is validated
 	public $location;

 	public $address;

 	public $contact;

 	// flag for the workflow : 1 is validated
 	public $status;

 	// who created the info ( 0 for a batch )
 	public $user;

 	// used to speed up print by server : 1 Paris , 2, Mtplr, 3 Eghézéee , 4 Other
 	public $zone;

 	function __construct() {
 		$this->title = '';
 		$this->content = '';
 		$this->thumb = '';
 		$this->origin = '';
 		$this->access = 1;
 		$this->licence = '';
 		$this->outGoingLink = '';
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
 		$this->creationDate = time();
 		$this->lastModifDate = time();
 		$this->location = array (
			"lat" => "",
			"lng" => "",
		);
		$this->address = array (
			"street" => "",
			"zipcode" => "",
			"city" => "",
			"country" => "",
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
 	}

 	function saveToMongoDB() {
 		$m = new Mongo();
		$db = $m->selectDB("yakwala");
		$place = $db->place;

		$record = array(
			"title"			=>	$this->title,
			"content" 		=>	$this->content,
			"thumb" 		=>	$this->thumb,
			"origin"		=>	$this->origin,	
			"access"		=>	$this->access,
			"licence"		=>	$this->licence,
			"outGoingLink" 	=>	$this->outGoingLink,
			"yakCat" 		=>	$this->yakCat,
			"creationDate" 	=>	new MongoDate(gmmktime()),
			"lastModifDate" =>	new MongoDate(gmmktime()),
			"location" 		=>	$this->location,
			"address" 		=>	$this->address,
			"contact"		=>	$this->contact,
			"status" 		=>	$this->status,
			"user"			=> 	$this->user, 
			"zone"			=> 	$this->zone,
		);	

		$place->save($record);
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
 	
 }