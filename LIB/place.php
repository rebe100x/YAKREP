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

 	// copy the licence of the file you used
 	public $licence;
 	
 	public $outGoingLink;

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
 		$this->licence = '';
 		$this->outGoingLink = '';
 		$this->yakTag = array();
		$this->yakCat = array();
		$this->humanCat = array();
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
			print "$this->title : already exists<br>";
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
    			print "$this->title : Updated<br>";
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
 			print "Call to Gmap ok<br>";
 			$this->location["lat"] = $loc["location"][0];
 			$this->location["lng"] = $loc["location"][1];
 			return true;
 		}
 		print "Gmap error for " . $query . "<br>";
 		return false;
 	}

 	function dropAllPlaces() {
 		$conf = new conf();
		$m = new Mongo(); 
		$db = $m->selectDB($conf->db());
 		$db->place->drop();
 	}
 	/* Save object in db
 	** Return value : 	_id : save done - 1 : Gmap error - 2 : updated duplicate 
	** 					- 3 : Duplicate without insertion
	**/
	function saveToMongoDB($locationQuery, $debug, $getLocation=true) {
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
			"licence"		=>	$this->licence,
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

		// Gestion des doublons
		$ret = $this->getDoublon();
		if ($ret == 0) {
			// Todo update every batch to get location before saving to db
			if ($getLocation == true) {
				if ($this->getLocation($locationQuery, $debug)) {
					$this->status = 1;
					$place->save($record);
					$place->ensureIndex(array("location"=>"2d"));
					print $this->title . " : location ok - saved in db<br>";
					return  $record['_id'];
				}
				else {
					$this->status = 10;
					$place->save($record);
					print $this->title . " : gmap  error - saved in db<br>";
					return 1;
				}
			}
			else {
				$place->save($record);
				$place->ensureIndex(array("location"=>"2d"));
				print $this->title . " : saved in db<br>";
				return  $record['_id'];
			}
		}
		else {
			return $ret;
		}
 	}
 	
 	function setTitle($title, $charset='utf-8')
 	{
 		$this->title = mb_convert_case($title, MB_CASE_TITLE, $charset);
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
 	
 	function setCatPrimaire() {
 		$this->humanCat[] = "Primaire";
 		$this->yakCat[] = new MongoId("5061a0d3fa9a95f009000000");
 	}
 	
 	function setCatCreche() {
 		$this->humanCat[] = "Creche";
 		$this->yakCat[] = new MongoId("506479f54a53042191030000");
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
 	
 	function setCatPatinoire() {
 		$this->humanCat[] = "Patinoire";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91000000");
 	}
 	
 	function setCatMaisonDeQuartier() {
 		$this->humanCat[] = "Maison de quartier";
 		$this->yakCat[] = new MongoId("506479f54a53042191040000");
 	}

	function setCatConcert() {
 		$this->humanCat[] = "Concert";
 		$this->yakCat[] = new MongoId("506479f54a53042191010000");
 	}
 	
 	function setCatSport() {
 		$this->humanCat[] = "Sport";
 		$this->yakCat[] = new MongoId("506479f54a53042191000000");
 	}
 	
 	function setCatVolley() {
 		$this->humanCat[] = "Volley";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91010000");
 	}
 	
 	function setCatPiscine() {
 		$this->humanCat[] = "Piscine";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91020000");
 	}

	function setCatGymnase() {
 		$this->humanCat[] = "Gymnase";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91030000");
 	}
 	
 	function setCatFootball() {
 		$this->humanCat[] = "Football";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91040000");
 	}
 	
 	function setCatRugby() {
 		$this->humanCat[] = "Rugby";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91050000");
 	}
 	
 	function setCatTennis() {
 		$this->humanCat[] = "Tennis";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91060000");
 	}
 	
 	function setCatPetanque() {
 		$this->humanCat[] = "Petanque";
 		$this->yakCat[] = new MongoId("50647e2d4a53041f91070000");
 	}
 	
 	function setCatStations() {
 		$this->humanCat[] = "Stations";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	} 

 	function setCatMusique() {
 		$this->humanCat[] = "Musique";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}

 	function setCatReligion() {
 		$this->humanCat[] = "Religion";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}

 	function setCatEspaceVert() {
 		$this->humanCat[] = "Espace Vert";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}

 	 function setCatArchive() {
 		$this->humanCat[] = "Archive";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
 	}

 	 function setCatExposition() {
 		$this->humanCat[] = "Exposition";
 		//$this->yakCat[] = new MongoId("504df728fa9a957c0b000007");
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

 	function prettyPrint() {

 		$str = "<div>\n";
 		$str .= "\t<h4>" . $this->title . "</h4>\n";
 		$str .= "\t<p>YakCats: ";

		if (!empty($this->humanCat)) {
	 		foreach ($this->humanCat as $key => $value) {
	 			$str .= $value . " ";
	 		}

	 		$str .= "</p>\n";
		}
 		if (!empty($this->yakTag)) {
	 		$str .= "\t<p>YakTags: ";

	 		foreach ($this->yakTag as $key => $value) {
	 			$str .= $value . " ";
	 		}

	 		$str .= "</p>\n";
	 	}
 		$str .= "</div>";
 		
 		return $str;
 	}
 }