<?php
require_once("conf.php");


class Zone
{
	private $selfColl;
	
	function __construct() {
		
		$this->conf = new Conf();
		
		$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());

		$this->selfColl = $db->zone;	
	}

 	
	/*
		Find the zone where the location is
		param : location = array('lat'=>float,'lng'=>float)
		output : a zone array
	*/
	function findNumByLocation($location){
		$res = array();
		$query = array(
							'box'=>array('$gt' => array('tr'=>array('lat'=>$location['lat']))),
							'box'=>array('$lt' => array('bl'=>array('lat'=>$location['lat']))),
							'box'=>array('$lt' => array('bl'=>array('lng'=>$location['lng']))),
							'box'=>array('$gt' => array('tr'=>array('lng'=>$location['lng']))),
							'status'=>1
							);
		$zones = $this->selfColl->find($query);
		foreach ($zones as $zone){
			$res[] = $zone['num'];
		}	
		return $res;
	}
}
