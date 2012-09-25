<?php

require_once("library.php");
require_once("../LIB/conf.php");



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

 	// copy the license of the file you used
 	public $license;

 	// [{ enfants:0/1 }{ handicapés:0/1 }{ personnes agées:0/1 }{ couvert, intérieur:0/1 }{ gay friendly:0/1 }{ gratuit:0/1 }{ animaux:0/1 }]
 	public $yakTag;

 	//Mongo ID idyakCat
	public $yakCat;

	//Human categories
	public $humanCat;

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
 		$this->license = '';
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
		$this->yakCat = array('');
		$this->humanCat = array('');
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

		$this->status = 1;
		$this->user = 0;
		$this->zone = 1;
 	}

 	function saveToMongoDB() {
		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());

 		$place = $db->place;

		$record = array(
			"title"			=>	$this->title,
			"content" 		=>	$this->content,
			"thumb" 		=>	$this->thumb,
			"origin"		=>	$this->origin,	
			"access"		=>	$this->access,
			"license"		=>	$this->license,
			"outGoingLink" 	=>	$this->outGoingLink,
			"yakCat" 		=>	$this->yakCat,
			"yakTag" 		=>	$this->yakTag,
			"creationDate" 	=>	new MongoDate(gmmktime()),
			"lastModifDate" =>	new MongoDate(gmmktime()),
			"location" 		=>	$this->location,
			"address" 		=>	$this->address,
			"contact"		=>	$this->contact,
			"status" 		=>	$this->status,
			"user"			=> 	$this->user, 
			"zone"			=> 	$this->zone,
		);	

		$rangeQuery = array('title' => $this->title, 'address' => $this->address);

		$doublon = $place->findOne($rangeQuery);
		if ($doublon != NULL) {
    		//print_r("Doublon avant:\n");
    		//var_dump($doublon);
    		if ($doublon['location'] == array()) {
    			$doublon['location'] = $this->location;
    		}
    		if ($doublon['contact'] == array()) {
    			$doublon['contact'] = $this->contact;
    		}
    		$doublon['lastModifDate'] = new MongoDate(gmmktime());
    		//print_r("Doublon apres:\n");
    		//var_dump($doublon);
    		return $doublon['_id'];
		}
		else {
			$place->save($record);
			$place->ensureIndex(array("location"=>"2d"));
			return $record['_id'];
		}
 	}

 	function getLocation($query, $debug) {
 		$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($query))),'PHP', $debug);

 		if ($loc != 0)
 		{
 			$this->location["lat"] = $loc["location"][0];
 			$this->location["lng"] = $loc["location"][1];
 			return true;
 		}
 		return false;
 	}

 	function setTel($tel, $type = "tel") {
		$this->contact["$type"] = mb_ereg_replace("[ /)(.-]","",$tel);
 	}

 	function setCatYakdico() {
 		$this->humanCat[] = "YakDico";
 		$this->yakCat[] = new MongoId("5056b7aafa9a95180b000000");
 	}

 	function setCatActu() {
 		$this->humanCat[] = "Actu";
 		$this->yakCat[] = new MongoId("504d89c5fa9a957004000000");
 	}
 	
	function setCatCulture() {
 		$this->humanCat[] = "Culture";
 		$this->yakCat[] = new MongoId("504d89cffa9a957004000001");
 	}

 	function setCatGeoloc() {
 		$this->humanCat[] = "Geoloc";
 		$this->yakCat[] = new MongoId("504d89f4fa9a958808000001");
 	}

 	function setCatEducation() {
 		$this->humanCat[] = "Education";
 		$this->yakCat[] = new MongoId("504dbb06fa9a95680b000211");
 	}
 	
 	function setCatElementaire() {
 		$this->humanCat[] = "Elementaire";
 		$this->yakCat[] = new MongoId("5056bae5fa9a95200b000001");
 	}
 	
 	function setCatMaternelle() {
 		$this->humanCat[] = "Maternelle";
 		$this->yakCat[] = new MongoId("5056baddfa9a95200b000000");
 	}

 	function setCatTheatre() {
 		$this->humanCat[] = "Theatre";
 		$this->yakCat[] = new MongoId("504df6b1fa9a957c0b000004");
 	}
 	
 	function setCatMusee() {
 		$this->humanCat[] = "Musee";
 		$this->yakCat[] = new MongoId("50535d5bfa9a95ac0d0000b6");
 	}
 	
 	function setCatExpo() {
 		$this->humanCat[] = "Expo";
 		$this->yakCat[] = new MongoId("504df70ffa9a957c0b000006");
 	}
 	
	function setCatPlanetarium() {
 		$this->humanCat[] = "Planetarium";
 		$this->yakCat[] = new MongoId("5056bf3ffa9a95180b000005");
 	}
 	
 	function setCatMediatheque() {
 		$this->humanCat[] = "Mediatheque";
 		$this->yakCat[] = new MongoId("5056bf35fa9a95180b000004");
 	}

 	function setCatAquarium() {
 		$this->humanCat[] = "Aquarium";
 		$this->yakCat[] = new MongoId("5056bf28fa9a95180b000003");
 	}

 	function setCatCinema() {
 		$this->humanCat[] = "Cinema";
 		$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}
 	
 	function setZoneParis() {
 		$this->zone = 1;
 	}

 	function setZoneMontpellier() {
 		$this->zone = 2;
 	}

 	function setZoneEghezee() {
 		$this->zone = 3;
 	}

 	function setZoneOther() {
 		$this->zone = 4;
 	}

 	function prettyPrint() {

 		$str = "<div>\n";
 		$str .= "\t<h4>" . $this->title . "</h4>\n";
 		$str .= "\t<p>YakCats: ";

 		foreach ($this->humanCat as $key => $value) {
 			$str .= $value . " ";
 		}

 		$str .= "</p>\n";

 		$str .= "</div>";
 		
 		return $str;
 	}
 }