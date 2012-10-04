<?php

require_once("library.php");
require_once("place.php");

 class Info{
 
 	// Configuration
 	private $conf;

 	// name of the info
 	public $title;
 	
 	// some text to describe the info
 	public $content;
 	
 	// a local link to a picture of the info
 	public $thumb;

 	// where did we get this info
 	public $origin;

	// mongoId (can be null if data is not coming from a file parsed)
	public $filesourceId;
	
	public $filesourceTitle;

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
	
	// Id of place
	public $placeid;

	function __construct() {
		$this->conf = new Conf();
		$this->title = '';
		$this->content = '';
		$this->thumb = '';
		$this->origin = '';
		$this->filesourceId = '';
		$this->filesourceTitle = '';
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

	/* Find duplicates in db
 	** Return values : 0 if no duplicate in db else 1
 	*/
	function getDoublon()
	{
		
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		$info = $db->info;

		//var_dump($this);
		if (!empty($this->location['lat']) && !empty($this->location['lng'])){
			//print_r($this->location);
			$rangeQuery = array('title' => $this->title, "location"=>array('$near'=>$this->location,'$maxDistance'=>0.000035));
		}
		else
			$rangeQuery = array('title' => $this->title, 'address' => $this->address );

		$doublon = $info->findOne($rangeQuery);
			
		if ($doublon != NULL) {
			print "Info already exists.<br>";
			return 1;
		}
		else {
			"Info doesn't exist. <br>";
			return 0;
		}
	}

	/* Find location for a place
 	** Input parameter : a query for gmap, status for debug (0 or 1)
 	** Return an array(X,Y). If no location, return false
 	*/
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
	
	/* Save Info in db
	** Input parameter : location query for gmap, debug 0 or 1,
	** 					 get location (true or false)
	** Return values : _id : info recorded - 1 : already exists
	*/
 	function saveToMongoDB($locationQuery, $debug, $getLocation=true) {
 		
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		$infoColl = $db->info;
		
		$this->setPlaceid($locationQuery, $debug);
		$this->setFilesourceId();

		// Gestion des doublons
		$ret = $this->getDoublon();

		if ($ret == 0) {
			if ($this->status != 10) {
				$dataNear = $infoColl->count(array("location"=>array('$near'=>$this->location,'$maxDistance'=>0.000035)));
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
			"filesourceId"	=>	$this->filesourceId,
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
			$infoColl->save($record);
			$infoColl->ensureIndex(array("location"=>"2d"));
			print "$this->title : info saved in db.<br>";
			return  $record['_id'];
		}
		else {
			print "$this->title : doublon. No insertion.<br>";
			return $ret;
		}
	}
	
	/* Find _id of a place to complete placeid field
	** if the place doesn't exist, create the place
	** Input parameter : location query for gmap, debug 0 or 1 
	*/
	function setPlaceid($locationQuery, $debug)
	{
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		$place = $db->place;

		if (!empty($this->address))
			$rangeQuery = array('title' => $this->title, 'status' => 1, 'address' => $this->address);
		else
			$rangeQuery = array('title' => $this->title, 'status' => 1);
			
		$result = $place->findOne($rangeQuery);
		if ($result != NULL) {
			if (!empty($result['location']))
				$this->location = $result['location'];

			// if info contact is not different from the place contact, clear info contact
			if ($result['contact'] == $this->contact) {
				foreach ($this->contact as &$value) {
					$value = "";
				}
			}
			$this->placeid = $result['_id'];

			print "Place already exists in db <br>";
		}
		else {
			print "$this->title : Place doesn't exist in db (creation).<br>";
			$this->placeid = $this->createPlace($locationQuery, $debug);
		}
	}

	/* Create a new place if doesn't exist
	** Input parameter : location query for call to gmap, debug parameter (0 or 1)
	** Return values : _id
	*/
	function createPlace($locationQuery, $debug)
	{
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		$place = $db->place;

		$newPlace = new Place();
		
		$newPlace->title = $this->title;
		$newPlace->content = $this->content;
		$newPlace->thumb = $this->thumb;
		$newPlace->origin = $this->origin;	
		$newPlace->filesourceId = $this->filesourceId;
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
		
		$newPlace->setFilesourceId();
		
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
	
	/* Drop all places in db (if dev environment)
 	** else do nothing
 	*/
 	function dropAllPlaces() {
 		if ($this->conf->getDeploy() == "dev") {
	 		$m = new Mongo(); 
			$db = $m->selectDB($this->conf->db());
	 		$db->place->drop();
 		}
 	}
 
 	/* Add telephone number in contact field
 	** Input parameter : a tel number, key word 'tel' or 'mobile'
 	*/
 	function setTel($tel, $type = "tel") {
		$this->contact["$type"] = mb_ereg_replace("[ /)(.-]","",$tel);
 	}

 	/* Add web site in contact field
 	** input parameter : a web site
	*/
 	function setWeb ($web) {
 		$pattern = "@^(http(s?)\:\/\/)?(www\.)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*(\/)?([a-z0-9][_a-z0-9\-\/\.\&\?\+\=\,]*)*$@i";
		
		$webArray = preg_split("/[\s]+/", $web);
		$result = preg_grep($pattern, $webArray);
		if (!empty($result)) {
			$result = array_values($result);
			$this->contact['web'] = $result[0];
		}		
 	}

 	/* Add email in contact field
 	** Input parameter : an email address
	*/
	function setMail ($mail) {
		$mail = strtolower($mail);
		if (filter_var($mail, FILTER_VALIDATE_EMAIL))
 			$this->contact['mail'] = $mail;
 	}

	/* Add yakCat to the place
 	** Input parameter : an array with yakCat to add (no accent, upper case)
 	** example : Array('CULTURE#CINEMA','#SPORT#PETANQUE');
 	*/
 	function setYakCat ($catPathArray) {
 		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
 		$yakCat = $db->yakcat;

 		$yakCatArray = iterator_to_array($yakCat->find());
 		foreach ($catPathArray as $catPath) {
 			foreach ($yakCatArray as $cat) {
 				if ($cat['pathN'] == strtoupper(suppr_accents(utf8_encode($catPath)))) {
 					$this->yakCat[] = $cat['_id'];
 					$this->humanCat[] = $cat['title'];
 				}
 			}
 		}
 	}
 	
 	
 	/* Search for the filesourceId in the DB and assign it to the specific field */
 	function setFilesourceId()
 	{
 		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
 		$filesource = $db->filesource;
 		
 		$res = $filesource->findOne(array('title'=>$this->filesourceTitle));
 		
 		if(!empty($res))
			$this->filesourceId = $res['_id'];                    
 	}
	
	/* Add tags to info
	*/
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
 	
 	/* Add zone to info
 	*/
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