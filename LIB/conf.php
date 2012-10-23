<?php 

ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);

require_once("library.php");
include_once "place.php";
include_once "info.php";
include_once "address.php";
include_once "contact.php";
include_once "location.php";
include_once "stringUtil.php";

class conf
{
	private $deploy = 'dev'; 
    private $db;
	private $fronturl;
	private $backurl;
	private $thumburl;
	

	function __construct(){
		
		switch($this->deploy){
			case 'dev':
				$this->db = 'yakwala';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.backend.yakwala.fr';
				$this->thumburl = 'http://dev.backend.yakwala.fr/BACKEND/thumb/';
				$this->thumbpath = '/BACKEND/thumb/';
				$this->bigpath = '/BACKEND/big/';
				$this->originalpath = '/BACKEND/original/';
				
			break;
			case 'preprod':
				$this->db = 'yakwala_preprod';
				$this->fronturl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com:62501';
				$this->backurl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/';
				$this->thumburl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BACKEND/thumb';				
				$this->thumbpath = '/PREPROD/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/PREPROD/YAKREP/BACKEND/big/';
				$this->originalpath = '/PREPROD/YAKREP/BACKEND/original/';
				
			break;
			case 'prod':
				$this->db = 'yakwala';
				$this->fronturl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com:62500';
				$this->backurl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/YAKREP/';
				$this->thumburl = 'http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/YAKREP/BACKEND/thumb';
				$this->thumbpath = '/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/YAKREP/BACKEND/big/';
				$this->originalpath = '/YAKREP/BACKEND/original/';
				
			break;
			
		}
		$m = new Mongo(); 
		$this->mdb = $m->selectDB($this->db());

	}

	public function getDeploy() {
		return $this->deploy;
	}
	
    public function db() {
        return  $this->db;
    }
	
	public function fronturl() {
        return  $this->fronturl;
    }
	
	public function backurl() {
        return  $this->backurl;
    }
	
	public function thumburl() {
        return  $this->thumburl;
    }
	public function mdb() {
        return  $this->mdb;
    }
	
	
}
?>