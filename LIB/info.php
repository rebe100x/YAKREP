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
 	public $license;
 	
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

	//Human categories
	public $humanCat;
	
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
	
	public $placeid;
 	

 	function __construct() {
 		$this->title = '';
 		$this->content = '';
 		$this->thumb = '';
 		$this->origin = '';
 		$this->access = 1;
 		$this->license = '';
 		$this->outGoingLink = '';
 		$this->heat = 80;
 		$this->print = 0;
 		$this->yakType = 0;
 		$this->yakCat = array();
 		$this->humanCat = array();
 		$this->yakTag = array();
		$this->yakCat = array();
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
		$this->placeid = '';
 	}

 	function getDoublon()
 	{
 		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
 		$place = $db->place;

 		$rangeQuery = array('title' => $this->title, 'address' => $this->address);
 		
		$doublon = $place->findOne($rangeQuery);
		$different = 0;
			
		if ($doublon != NULL) {
			//print_r($doublon['contact']);
			foreach ($doublon['contact'] as $key => &$value) {
				if (empty($value) && !empty($this->contact[$key])) {
					$value = $this->contact[$key];
					//print "$key : " . $this->contact[$key] . "<br/>";
					$different++;
				}
			}
			//Updated duplicate
    		if ($different > 0) {
    			//var_dump($doublon);
	    		$update = array('contact' => $doublon['contact'], 'lastModifDate' => new MongoDate(gmmktime()));
	    		$place->update($rangeQuery, $update);
	    		//var_dump($doublon);
	    		return 2;
	    	}
    		return 3;
		}
		else {
			return 0;
		}
 	}

 	function getLocation($query, $debug) {
 		$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($query))),'PHP', $debug);
 		//print_r($loc);

 		if ($loc['status'] == "OK")
 		{
 			$this->location["lat"] = $loc["location"][0];
 			$this->location["lng"] = $loc["location"][1];
 			return true;
 		}
 		print "Gmap error for address : " . $loc['address'] . "<br>";
 		return false;
 	}
 	
 	function saveToMongoDB($locationQuery, $debug) {
 		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
		$info = $db->info;
		
		$this->setPlaceid();

		$record = array(
			"title"			=>	$this->title,
			"content" 		=>	$this->content,
			"thumb" 		=>	$this->thumb,
			"origin"		=>	$this->origin,	
			"access"		=>	$this->access,
			"license"		=>	$this->license,
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

		// Gestion des doublons
		$ret = $this->getDoublon();
		if ($ret == 0) {
			if ($this->getLocation($locationQuery, $debug)) {
				$info->save($record);
				$info->ensureIndex(array("location"=>"2d"));
				return  $record['_id'];
			}
			else {
				return 1;
			}
		}
		else {
			return $ret;
		}
 	}
 	
 	function setPlaceid()
 	{
 		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
 		$place = $db->place;

 		$rangeQuery = array('title' => $this->title);
 		
		$result = $place->findOne($rangeQuery);
		if ($result != NULL) 
			$this->placeid = $result['_id'];
 	}
 	
 	function setTitle($title)
 	{
 		$this->title = ucwords(strtolower($title));
 	}

 	//type = tel or mobile
 	function setTel($tel, $type = "tel") {
		$this->contact["$type"] = mb_ereg_replace("[ /)(.-]","",$tel);
 	}

 	function setWeb ($web) {
 		$pattern = "@^(http(s?)\:\/\/)?(www\.)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*(\/)?([a-z0-9][_a-z0-9\-\/\.\&\?\+\=\,]*)*$@i";
		if (preg_match($pattern, $web))
			$this->contact['web'] = $web;
 	}

	function setMail ($mail) {
		$mail = strtolower($mail);
		if (filter_var($mail, FILTER_VALIDATE_EMAIL))
 			$this->contact['mail'] = $mail;
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
 	
 	function setCatEcole() {
 		$this->humanCat[] = "Ecole";
 		$this->yakCat[] = new MongoId("5056b89bfa9a95180b000001");
 	}
 	
 	function setCatPrimaire() {
 		$this->humanCat[] = "Primaire";
 		$this->yakCat[] = new MongoId("5061a0d3fa9a95f009000000");
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
 	
 	function setTagChildren() {
 		$this->yakTag[] = "Children";
 	}

 	function setTagDisabled() {
 		$this->yakTag[] = "Disabled";
 	}

	function setTagElderly() {
 		$this->yakTag[] = "Elderly person";
 	}

 	function setTagIndoor() {
 		$this->yakTag[] = "Indoor";
 	}

 	function setTagGay() {
 		$this->yakTag[] = "Gay friendly";
 	}

 	function setTagFree() {
 		$this->yakTag[] = "Free";
 	}

 	function setTagPets() {
 		$this->yakTag[] = "Pets";
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
 	
 }