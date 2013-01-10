<?php


 class Location{
	
	public $lat,$lng;


 	function __construct() {
		
		$this->lat = 0;
		$this->lng = 0;
 	}
	
	function set($lat,$lng){
		$this->lat = (float)($lat);
		$this->lng = (float)($lng);
	}

}
 