<?php

require_once("library.php");
require_once("place.php");

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

		//var_dump($this);
		if (!empty($this->location['lat']) && !empty($this->location['lng'])){
			//print_r($this->location);
			$rangeQuery = array('title' => $this->title, "location"=>array('$near'=>$this->location,'$maxDistance'=>0.000035));
		}
		else
			$rangeQuery = array('title' => $this->title, 'address' => $this->address );

		$doublon = $place->findOne($rangeQuery);
			
		if ($doublon != NULL) {
			print "Info already exists.<br>";
			return 1;
		}
		else {
			"Info doesn't exist. <br>";
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
		print "Gmap error for address : " . $query . "<br>";
		return false;
	}
	
	function saveToMongoDB($locationQuery, $debug) {
		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
		$info = $db->info;
		
		$this->setPlaceid($locationQuery, $debug);

		// Gestion des doublons
		$ret = $this->getDoublon();

		if ($ret == 0) {
			if ($this->status != 10) {
				$dataNear = $info->count(array("location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
				print $dataNear . " infos near ". $this->title . "<br>";
				if($dataNear > 0 ){
					$delta = ceil($dataNear/12);
					$this->location = array("lat"=>(0.000015*sin(3.1415*$dataNear/6)+$this->location['lat']),"lng"=>(0.00002*cos(3.1415*$dataNear/6)+$this->location['lng']));
					print "Slightly move data to avoid superposition<br/>";
				}
			}

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
			$info->save($record);
			$info->ensureIndex(array("location"=>"2d"));
			print "$this->title : info saved in db.<br>";
			return  $record['_id'];
		}
		else {
			print "$this->title : doublon. No insertion.<br>";
			return $ret;
		}
	}
	
	function setPlaceid($locationQuery, $debug)
	{
		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
		$place = $db->place;

		if (!empty($this->address))
			$rangeQuery = array('title' => $this->title, 'status' => 1, 'address' => $this->address);
		else
			$rangeQuery = array('title' => $this->title, 'status' => 1);
			
		$result = $place->findOne($rangeQuery);
		if ($result != NULL) {
			if (!empty($result['location']))
				$this->location = $result['location'];

			$this->placeid = $result['_id'];
			print "Place already exists in db <br>";
		}
		else {
			print "$this->title : Place doesn't exist in db (creation).<br>";
			$this->placeid = $this->createPlace($locationQuery, $debug);
		}
	}

	function createPlace($locationQuery, $debug)
	{
		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
		$place = $db->place;

		$newPlace = new Place();
		
		$newPlace->title = $this->title;
		$newPlace->content = $this->content;
		$newPlace->thumb = $this->thumb;
		$newPlace->origin = $this->origin;	
		$newPlace->access = $this->access;
		$newPlace->license = $this->license;
		$newPlace->outGoingLink = $this->outGoingLink;
		$newPlace->yakCat = $this->yakCat;
		$newPlace->yakTag = $this->yakTag;
		$newPlace->creationDate = new MongoDate(gmmktime());
		$newPlace->lastModifDate = new MongoDate(gmmktime());
		$newPlace->location = $this->location;
		$newPlace->address = $this->address;
		$newPlace->contact = $this->contact;
		$newPlace->status = $this->status;
		$newPlace->user = $this->user; 
		$newPlace->zone = $this->zone;
		
		if ($newPlace->getLocation($locationQuery, $debug)) {
			$newPlace->status = 1;
			print "Location ok.<br>";
			$this->location = $newPlace->location;
		}
		else {
			$newPlace->status = 10;
			print "Gmap error.<br>";
		}
		//save in db
		$record = array(
			"title"			=>	$newPlace->title,
			"content" 		=>	$newPlace->content,
			"thumb" 		=>	$newPlace->thumb,
			"origin"		=>	$newPlace->origin,	
			"access"		=>	$newPlace->access,
			"license"		=>	$newPlace->license,
			"outGoingLink" 	=>	$newPlace->outGoingLink,
			"yakCat" 		=>	$newPlace->yakCat,
			"yakTag" 		=>	$newPlace->yakTag,
			"creationDate" 	=>	new MongoDate(gmmktime()),
			"lastModifDate" =>	new MongoDate(gmmktime()),
			"location" 		=>	$newPlace->location,
			"address" 		=>	$newPlace->address,
			"contact"		=>	$newPlace->contact,
			"status" 		=>	$newPlace->status,
			"user"			=> 	$newPlace->user, 
			"zone"			=> 	$newPlace->zone,
		);
		$place->save($record);
		$place->ensureIndex(array("location"=>"2d"));
		print "Place created and saved in db.<br>";
		$this->status = $newPlace->status;
		return  $record['_id'];
	}
	
	function dropAllPlaces() {
		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
		$db->place->drop();
		$db->info->drop();
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
		
		$webArray = preg_split("/[\s]+/", $web);
		$result = preg_grep($pattern, $webArray);
	
		if (!empty($result))
			$this->contact['web'] = $result[0];
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