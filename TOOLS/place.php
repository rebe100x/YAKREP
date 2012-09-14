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
 		$this->yakTag = array (
			"enfants" => "0",
			"handicapés" => "0",
			"personnes agées" => "0",
			"couvert, intérieur" => "0",
			"gay friendly" => "0",
			"gratuit" => "0",
			"animaux" => "0",
		);
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

 	function getLocation($query, $debug)
 	{
 		$loc = getLocationGMap($qury,'PHP', $debug);

 		if ($loc != 0)
 		{
 			$this->location["lat"] = $loc["location"]["lat"];
 			$this->location["lng"] = $loc["location"]["lng"];
 		}
 	}

 	

 }