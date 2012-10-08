<?php
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once("library.php");
//require_once("$root/LIB/conf.php");
require_once("conf.php");


 class Place
 {
	//collection
	private $placeColl;
	//yakcat collection
	private $yakCatColl;
	//filesource collection
	private $filesourceColl;
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

	// array of address elements
 	public $address;
	
	// human readable address
	public $formatted_address;

 	public $contact;

 	// flag for the workflow : 1 is validated
 	public $status;

 	// who created the info ( 0 for a batch )
 	public $user;

 	// used to speed up print by server : 1 Paris , 2, Mtplr, 3 Eghézéee , 4 Other
 	public $zone;

 	function __construct() {
		
		$this->conf = new Conf();
		
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());

		$this->placeColl = $db->place;	
		$this->yakCatColl = $db->yakcat;
		$this->filesourceColl = $db->filesource;
 		
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
			"street_number" => "",
			"street" => "",
			"arr" => "",
			"city" => "",
			"state" => "",
			"area" => "",
			"country" => "",
			"zip" => "",
		);
		$this->formatted_address = "";
		
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

 


 	/* Drop all places in db (if dev environment)
 	** else do nothing
 	*/
 	function dropAllPlaces() {
 		if ($this->conf->getDeploy() == "dev" || $this->conf->getDeploy() == "preprod") {
	 		
	 		$this->placeColl->drop();
 		}
 	}

 	/* INPUT : 
	* locationQuery : the query to gmap. If null, we assume we already have the address and location in the file
	* debug = 1 to print debug
	* flagUpdate to update duplicated records in db with the input one
	* 
	** Return value : 	$res = array('insert','locErr','update','callGMAP')
	**/
	
	function saveToMongoDB($locationQuery = "", $debug, $flagUpdate = false) {
		
 		
		$res = array('insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0);
		
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
			"formatted_address" 		=>	$this->formatted_address,
			"contact"		=>	$this->contact,
			"status" 		=>	$this->status,
			"user"			=> 	$this->user, 
			"zone"			=> 	$this->zone,
		);

		// Gestion des doublons
		$rangeQuery = array('title' => $this->title, 'zone' => $this->zone);
		$doublon = $this->placeColl->findOne($rangeQuery);
		
		// if no duplicated
		if (!$doublon) {
			// if we asked for a geoloc
			if ( strlen($locationQuery)>0) {
				$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($locationQuery))),'PHP', $debug);
				$res['callGMAP'] = 1;
				if ($loc['status'] ==  'OK') {
					// transfert GMAP result to DB :
					$record['location'] = $loc['location'];
					$record['address'] = $loc['address'];
					$record['formatted_address'] = $loc['formatted_address'];
					$this->status = 1;
				}
				else {
					$this->status = 10;
					$res['locErr'] = 1;
					echo '<br>Err: GMAP did not return result '.$locationQuery;
				}
			} else {
				$this->status = 1;
			}
			
			$this->placeColl->save($record);
			$this->placeColl->ensureIndex(array("location"=>"2d"));
			$this->placeColl->ensureIndex(array("title"=>1,"status"=>1,"zone"=>1));
			if($record['_id']){
				echo $record['_id'];
				$res['insert'] = 1;
			}
		}else{ // if already in db
			if($flagUpdate == 1){ // if we are asked to update
				if (strlen($locationQuery) > 0) { // if we are asked to get the location
					$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($locationQuery))),'PHP', $debug);
					$res['callGMAP'] = 1;
					$record['location'] = $loc['location'];
					$record['address'] = $loc['address'];
					$record['formatted_address'] = $loc['formatted_address'];
				}
				$this->placeColl->update(array("_id"=>$doublon['_id']),$record);
				$res['update'] = 1;
			}
		}
		
		return  $res;
 	}
 	
 	/* Set telephone number in contact field
 	** Input parameter : a tel number, key word 'tel' or 'mobile'
 	*/
 	function setTel($tel, $type = "tel") {
		$this->contact["$type"] = mb_ereg_replace("[ /)(.-]","",$tel);
 	}

 	/* Set web site in contact field
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

 	/* Set email in contact field
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
 		
		
 		$yakCatArray = iterator_to_array($this->yakCatColl->find());
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
 		$res = $this->filesourceColl->findOne(array('title'=>$this->filesourceTitle));
 		
 		if(!empty($res))
			$this->filesourceId = $res['_id'];  
		else{
			echo "No file resource with the name <b>".$this->filesourceTitle."</b>. Please check in db and create the record";
			exit;
		}
		
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