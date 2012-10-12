<?php

require_once("conf.php");

 class Info extends Place{
 
 	// Configuration
 	public $conf;

	//collection
	public $placeColl;
	//yakcat collection
	public $yakCatColl;
	//info collection
	public $infoColl;
	//filesource collection
	public $filesourceColl;
	
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
	
	// the address human readable for printing ( formated address )
	public $address;
	
	// Name of place
	public $placeName;
	// Id of place
	public $placeid;

	function __construct() {
		$this->conf = new Conf();
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		$this->infoColl = $db->info;
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
		$this->address = ''; // NOTE that in INFO, the address is only a human readable string and in PLACE, it is an object
		$this->location = new Location();
		$this->contact = new Contact();
		$this->status = 0;
		$this->user = 0;
		$this->zone = 0;
		$this->placeid = '';
		$this->placeName = '';
	}

	/* Find duplicates in db
 	** Return values : 0 if no duplicate in db else 1
 	*/
	function getDoublon()
	{
		//var_dump($this);
		if( !empty($this->location->lat) && !empty($this->location->lng) ){
			//print_r($this->location);
			$rangeQuery = array('title' => $this->title, "location"=>array('$near'=>$this->location,'$maxDistance'=>0.000035));
		}
		
		$doublon = $this->infoColl->findOne($rangeQuery);
		
		
		return $doublon;
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
			$this->location->lat = $loc["location"][0];
			$this->location->lng = $loc["location"][1];
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
 	function saveToMongoDB($locationQuery = "", $debug, $flagUpdate = false) {
 		
		// must be set : ZONE and PLACENAME or ADDRESS
		// and ORIGIN and FILETITLE and LICENCE
		$res = array('duplicate'=>0,'insert'=>0,'getPlaceinDB'=>0,'insertPlace'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0);
		
		$this->setFilesourceId();
		
		//$this->setPlaceid($locationQuery, $debug);

		// if we have a place geolocalized
		if($this->placeName || $this->address){
			$resPlace = $this->linkToPlace($locationQuery, $debug);
		
			// logs
			if($resPlace['insert']==0)
				$res['getPlaceinDB']++;
			else{
				$res['duplicate'] = $resPlace['duplicate'];
				$res['insertPlace'] = $resPlace['insert'];
				$res['locErr'] = $resPlace['locErr'];
				$res['callGMAP'] = $resPlace['callGMAP'];
				
			}
			$doublon = $this->getDoublon();
			
			if(!$doublon){
				$this->moveData();
				$this->saveInfo();
				echo '<br>save for the map<br>';
				$res['insert'] ++;
			}else{
				$res['duplicate'] = 1;	
				if($flagUpdate == 1){ // if we are asked to update
					
					
					$this->updateInfo($doublon['_id']);
					$res['update'] = 1;
				}
			}
			
			
			
		}else{ // info is not geolocalised by semantic tool, we put it in the feed with the zone area information
				$this->print = 0;
				$this->status = 1;
				// @TODO in soft code
				switch($this->zone){
				case '1':
					$lat = 48.851875;
					$lng = 2.356374;
				break;
				case '2':
					$lat = 43.610787;
					$lng = 3.876715;
				break;
				case '3':
					$lat = 50.583346;
					$lng = 4.900031;
				break;
				default:
					$lat = 48.851875;
					$lng = 2.356374;
				}
				
				$this->location->lat = $lat;
				$this->location->lng = $lng;
				
				$this->saveInfo();
				echo '<br>info save for the feed<br>';
		}
		
		return $res;
	}
	
	
	private function saveInfo(){
			
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
			"pubDate"		=>	new MongoDate(gmmktime()),
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
			$this->infoColl->save($record);
			$this->infoColl->ensureIndex(array("location"=>"2d","pubDate"=>-1,"yakType"=>1,"print"=>1,"status"=>1));
			print "$this->title : info saved in db.<br>";
			return  $record['_id'];
	
	
	}
	
	
	private function updateInfo($id){
			
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
			"pubDate"		=>	new MongoDate(gmmktime()),
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
			$this->infoColl->update(array("_id"=>$id),$record);
			$this->infoColl->ensureIndex(array("location"=>"2d","pubDate"=>-1,"yakType"=>1,"print"=>1,"status"=>1));
			print "$this->title : info updated in db.<br>";
			return  $record['_id'];
	
	
	}
	/* Link the info to a place
	** if the place doesn't exist in db, we create the place
	** if it is in db, we get the data to put it in the info
	
	** set the info.placeId and the info.location and the info.status and info.print and the info.zone
	
	** Input parameter : location query for gmap, debug 0 or 1 
	** Output : the result of the place creation ( null if no creation ) 
	*/
	function linkToPlace($locationQuery, $debug)
	{
		$newPlace = new Place();
		$resPlace = array('duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());
		// we try first on the name of the place
		if (!empty($this->placeName))
			$theString2Search = $this->placeName;
		elseif (!empty($this->address)) // but if no place name, we try the address
			$theString2Search = $this->address;
			
		$result = $newPlace->getDuplicated($theString2Search,$this->zone);
		if ($result != NULL) {			
			if (!empty($result['location'])) // we set the location without calling gmap
				$this->location = $result['location'];
				$this->placeid = $result['_id'];
				$this->status = 1; // here it must be 1 because of the query
				$this->print = 1; 
				$this->address = $result['title'];
		}
		else {
			print "$this->title : Place doesn't exist in db (creation).<br>";
			
			
			$newPlace->title = $this->placeName;			
			$newPlace->origin = $this->origin;
			$newPlace->filesourceTitle = $this->filesourceTitle;
			$newPlace->licence = $this->licence;
			$cat = array("GEOLOCALISATION", "GEOLOCALISATION#YAKDICO");
			$newPlace->setYakCat($cat);
			$newPlace->zone = $this->zone;
			
			$resPlace = $newPlace->saveToMongoDB($locationQuery, $debug);
			if(!empty($resPlace['error'])){
				echo $resPlace['error'];
				echo '<br><b>BATCH FAILLED</b><br>Place creation failed';
				exit;
			}
			
			$theNewPlace = $resPlace['record'];
			$this->placeid = $theNewPlace['_id'];
			$this->location = $theNewPlace['location'];
			$this->address = $theNewPlace['formatted_address'];
			$this->status = $theNewPlace['status']; // if gmap did not work we have a status 10
			if($this->status != 1)
				$this->print = 0; 	
			else
				$this->print = 1; 	
			
			
		}
		return $resPlace;
	}

	
	/* move slightly an info to avoid superposition
	* 
	*
	*/
	private function moveData(){
		
		$dataCount = 0;
		// here we take only 30 days of max history
		$dataCount = $this->infoColl->count(array(
											"location"=>array('$near'=>$this->location,'$maxDistance'=>0.000035),
											"pubDate"=>array('$gte'=>new MongoDate(gmmktime()-86400*30)) 
											)
									);

		// if more than one info on the same location
		if($dataCount > 0){
			$this->location = (object)$this->location;
			$this->location = randomPositionArround(array('lat'=>$this->location->lat,'lng'=>$this->location->lng));
		}

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
 //$res = array('duplicate'=>0,'insert'=>0,'getPlaceinDB'=>0,'insertPlace'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0);
 //$resPlace = array('duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());
 //$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0);
 function prettyLog($results){
	print "<br>________________________________________________<br>";
    print "Rows : " . $results['row'] . "<br>";
	print "Rejected : " . $results['rejected'] . "<br>";
	print "Parsed : " . $results['parse'] . "<br>";
	print "Duplicated : " . $results['duplicate'] . "<br>";
	print "Place in db : " . $results['getPlaceinDB'] . "<br>";
	print "Place created : " . $results['insertPlace'] . "<br>";
    print "Call to gmap : " . $results['callGMAP'] . "<br>";
    print "Location error (call gmap) : " . $results['locErr'] . "<br>";
    print "Insertions : " . $results['insert'] . "<br>";
    print "Updates : " . $results['update'] . "<br>";
    print "<i>to force update, use ?updateFlag=1</i>";

}	

}