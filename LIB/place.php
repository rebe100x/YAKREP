<?php
require_once("conf.php");



class Place
{
	//collection
	public $placeColl;
	//yakcat collection
	public $yakCatColl;
	//filesource collection
	public $filesourceColl;
 	//Conf
	public $conf;
 	// name of the place ( can be a building, an area, a street ... )
	public $title;
 	// some text to describe the place
	public $content;
 	// a local link to a picture of the place
	public $thumb;
 	// where did we get this info
	public $origin;
	// mongoId (can be null if data is not coming from a file parsed) but never null in a batch
	public $filesourceId;
	// name of the file source
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

 	// location object
	public $location;

	// array of address elements
	public $address;
	
	// human readable address
	public $formatted_address;

	// contact object
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
		$this->freeTag = array();
		$this->creationDate = time();
		$this->lastModifDate = time();
		$this->location = new Location();
		$this->address = new Address();
		$this->formatted_address = "";
		$this->contact = new Contact();
		$this->status = 0;
		$this->user = 0;
		$this->zone = 0;
	}

 	/* Drop all places in db (if dev environment)
 	** else do nothing
 	*/
 	function dropAllPlaces() {
 		if ($this->conf->getDeploy() == "dev" || $this->conf->getDeploy() == "preprod") {

 			$this->placeColl->drop();
 		}
 	}

	/*
	* 
	*
	*/
	function getDuplicated($title,$zone,$thestatus=1){

		$theString2Search = StringUtil::accentToRegex(preg_quote($title));
		$rangeQuery = array('title' => new MongoRegex("/.*{$theString2Search}.*/i"),'zone' => $zone,"status"=>$thestatus); // TODO : status 1 or 2  and 3 throw an alert

		$doublon = $this->placeColl->findOne($rangeQuery);

		return $doublon;
	}

	/*
	 * locationQuery : the query to gmap
	 * res : reference to return value from saveToMongoDB
	 */
	 function getLocationFromQuery($locationQuery='', &$res) {
		$loc = getLocationGMap(urlencode(utf8_decode(suppr_accents($locationQuery))),'PHP', $debug);
		
		$res['callGMAP'] = 1;

		if ($loc['status'] ==  'OK') {
			// transfert GMAP result to DB :
			
			$this->location->lat = $loc['location'][0];
			$this->location->lng = $loc['location'][1];
			$this->address = (object)($loc['address']);
			$this->formatted_address = $loc['formatted_address'];
			$this->status = 1;
		}
		else {
			$this->status = 10;
			$res['locErr'] = 1;

			echo '<br>Err: GMAP did not return result '.$locationQuery;
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
		
		$res = array('duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

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
			"formatted_address" =>	$this->formatted_address,
			"contact"		=>	$this->contact,
			"status" 		=>	$this->status,
			"user"			=> 	$this->user, 
			"zone"			=> 	$this->zone,
			);

			//print_r($record);
		$doublon = $this->getDuplicated($this->title,$this->zone,$this->status);

		var_dump($doublon);
		// if no duplicated
		if (empty($doublon)) {
			
				// if we asked for a geoloc
			if ( strlen($locationQuery)>0 ) {
			   //locationQuery="sdfsdfsd";
			   //$res=array();
				$this->getLocationFromQuery($locationQuery, $res);
			
			} 

			$resSave = $this->savePlace($record);
			//print_r($resSave);
			$res['record'] = $record; // TODO : cast to array ???
			$res['error'] = $resSave['error'];
			$this->placeColl->ensureIndex(array("location"=>"2d"));
			$this->placeColl->ensureIndex(array("title"=>1,"status"=>1,"zone"=>1));
			if(empty($res['error'])){
				$res['insert'] = 1;
			}
		}
		else{ // if already in db
			$res['duplicate'] = 1;
			$res['record'] = $doublon;

			if($flagUpdate == 1){ // if we are asked to update
				$record = $res['record'];

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


	/* Save the place after a check on param not nullable
	* Input : record
	* Output : the error text if there is one
	*/
	private function savePlace($record){
		
		$res = array('error'=>'','id'=>'');
		// protect not nullable fields
		if(strlen(trim($this->title)) > 0 
			&& strlen(trim($this->licence)) > 0 
			&& strlen(trim($this->origin)) > 0
			&& strlen(trim($this->filesourceTitle)) > 0
			&& (!empty($this->zone))
			&& sizeof($this->yakCat) > 0
			&& (!empty($this->status))
			&& (!empty($this->access))		
			){
			$test=$this->placeColl->save($record);
			print_r($record);
		$res['id'] = $record['_id'];
	}else{
		$res['error'] = "<br><b>Error:</b> A non nullable field is empty :<br>".
		"<b>title:</b> ".$this->title."<br>" .
		"<b>licence:</b> ".$this->licence."<br>" .
		"<b>origin:</b> ".$this->origin."<br>" .
		"<b>filesourceTitle:</b> ".$this->filesourceTitle."<br>" .
		"<b>zone:</b> ".$this->zone."<br>" . 
		"<b>yakCat:</b> ".serialize($this->yakCat)."<br>".
		"<b>Status:</b> ".$this->status."<br>".
		"<b>Access:</b> ".$this->access."<br>";
	}

	return $res;
}
 	/* Set telephone number in contact field
 	** Input parameter : a tel number, key word 'tel' or 'mobile'
 	*/
 	function setTel($tel, $type = "tel") {
 		$this->contact->tel = mb_ereg_replace("[ /)(.-]","",$tel);
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
 			$this->contact->web = $result[0];
 		}

 	}

 	/* Set email in contact field
 	** Input parameter : an email 


	*/
 	function setMail ($mail) {
 		$mail = strtolower($mail);
 		if (filter_var($mail, FILTER_VALIDATE_EMAIL))
 			$this->contact->mail = $mail;
 	}

 	/* Add yakCat to the place
 	** Input parameter : an array with yakCat to add (no accent, upper case)
 	** example : Array('CULTURE#CINEMA','#SPORT#PETANQUE');
 	*/
 	public function setYakCat ($catPathArray) {
 		
		//var_dump($this);
 		$yakCatArray = iterator_to_array($this->yakCatColl->find());
 		foreach ($catPathArray as $catPath) {
 			foreach ($yakCatArray as $cat) {
 				if ( $cat['pathN'] == strtoupper(suppr_accents(utf8_encode($catPath))) 
 					|| "#".$cat['pathN'] == strtoupper(suppr_accents(utf8_encode($catPath))) ) {
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
 	$this->location->lat = (float)$latitude;
 	$this->location->lng = (float)$longitude;
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
 	function setTagCarPark() {
 		$this->yakTag[] = "Car park";
 	}

	/*SET THE ZONE
	* input : zone name
	* return : nothing
	*/
	function setZone($zoneName){
		$err = 1;
		switch($zoneName){
			case 'PARIS':
			$zone = 1;
			break;
			case 'MONTPELLIER':
			$zone = 2;
			break;
			case 'EGHEZEE':
			$zone = 3;
			break;
			case 'REGION DE BRUXELLES':
			$zone = 4;
			break;
			case 'REGION WALLONNE':
			$zone = 5;
			break;
			case 'REGION FLAMANDE':
			$zone = 6;
			break;
			default:
			$zone = 0;
			$err = "ZONE NOT KNOWN";

		}
		
		$this->zone = $zone;
		return $err;
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
	
	function prettyLog($results){
		print "<br>________________________________________________<br>";
		print "Rows : " . $results['row'] . "<br>";
		print "Rejected : " . $results['rejected'] . "<br>";
		print "Parsed : " . $results['parse'] . "<br>";
		print "Duplicated : " . $results['duplicate'] . "<br>";
		print "Call to gmap : " . $results['callGMAP'] . "<br>";
		print "Location error (call gmap) : " . $results['locErr'] . "<br>";
		print "Insertions : " . $results['insert'] . "<br>";
		print "Updates : " . $results['update'] . "<br>";
		print "<i>to force update, use ?updateFlag=1</i>";

	}	

}