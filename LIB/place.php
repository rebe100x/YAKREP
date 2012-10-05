<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("library.php");
require_once("$root/LIB/conf.php");



 class Place
 {
 	//Conf
 	private $conf;

 	// name of the place ( can be a building, an area, a street ... )
 	public $title;
 	
 	// some text to describe the place
 	public $content;
 	
 	// a local link to a picture of the place
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
 		$this->conf = new Conf();
 		$this->title = '';
 		$this->content = '';
 		$this->thumb = '';
 		$this->origin = '';
 		$this->filesourceId = '';
 		$this->filesourceTitle = '';
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

 	/* Find duplicates in db
 	** if duplicate exists, update contact fields if different
 	** Return values : 0 no duplicate - 2 updated - 3 duplicate, do nothing
 	*/
 	function getDoublon()
 	{
 		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
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

 	/* Find location for a place
 	** Input parameter : a query for gmap, status for debug (0 or 1)
 	** Return an array(X,Y). If no location, return false
 	*/
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

 	/* Save object in db
 	** Return value : 	_id : save done - 1 : Gmap error - 2 : updated duplicate 
	** 					- 3 : Duplicate without insertion
	**/
	function saveToMongoDB($locationQuery, $debug, $getLocation=true) {
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
 		$place = $db->place;
 		
		$this->setFilesourceId();

		$record = array(
			"title"			=>	$this->title,
			"content" 		=>	$this->content,
			"thumb" 		=>	$this->thumb,
			"origin"		=>	$this->origin,
			"filesourceId"	=>	$this->filesourceId,	
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

 	/* Set place location manually */
 	function setLocation($latitude, $longitude) 
 	{
 		$this->location["lat"] = $latitude;
 		$this->location["lng"] = $longitude;
 	}

 	/* Add Tags in yakTag array
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
	
	function setTagOutdoor() {
 		$this->yakTag[] = "Outdoor";
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