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
include_once "zone.php";
include_once "stringUtil.php";
//include_once "cat.php";

class conf
{
	public $deploy = 'devrenaud'; 
    private $db;
	private $fronturl;
	private $backurl;
	private $thumburl;
	private $bigpath;
	private $originalpath;
	
	function __construct(){
		
		switch($this->deploy){
			case 'dev':
				$this->db = 'yakwala';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.batch.yakwala.fr';
				$this->thumburl = 'http://dev.batch.yakwala.fr/BACKEND/thumb/';
				$this->thumbpath = '/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/YAKREP/BACKEND/big/';
				$this->mediumpath = '/YAKREP/BACKEND/medium/';
				$this->originalpath = '/YAKREP/BACKEND/original/';
				
			break;
			case 'devrenaud':
				$this->db = 'yakwala';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.batch.yakwala.fr';
				$this->thumburl = 'http://dev.batch.yakwala.fr/BACKEND/thumb/';
				$this->thumbpath = '/BACKEND/thumb/';
				$this->bigpath = '/BACKEND/big/';
				$this->mediumpath = '/BACKEND/medium/';
				$this->originalpath = '/BACKEND/original/';
				
			break;
			case 'preprod':
				$this->db = 'yakwala_preprod';
				$this->fronturl = 'http://labs.yakwala.fr';
				$this->backurl = 'http://batch.yakwala.fr/PREPROD/YAKREP/';
				$this->thumburl = 'http://batch.yakwala.fr/PREPROD/YAKREP/BACKEND/thumb';				
				$this->thumbpath = '/PREPROD/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/PREPROD/YAKREP/BACKEND/big/';
				$this->mediumpath = '/PREPROD/YAKREP/BACKEND/medium/';
				$this->originalpath = '/PREPROD/YAKREP/BACKEND/original/';
				
			break;
			case 'prod':
				$this->db = 'yakwala';
				$this->fronturl = 'http://labs.yakwala.fr';
				$this->backurl = 'http://batch.yakwala.fr/YAKREP/';
				$this->thumburl = 'http://batch.yakwala.fr/YAKREP/BACKEND/thumb';				
				$this->thumbpath = '/PROD/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/PROD/YAKREP/BACKEND/big/';
				$this->mediumpath = '/PROD/YAKREP/BACKEND/medium/';
				$this->originalpath = '/PROD/YAKREP/BACKEND/original/';
				
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

	public function thumbpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->thumbpath;
    }

	public function bigpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->bigpath;
    }

	public function originalpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->originalpath;
    }

	public function mdb() {
        return  $this->mdb;
    }
	
	
}
?>