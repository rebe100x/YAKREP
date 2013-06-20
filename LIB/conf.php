<?php 

ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);

require_once("aws-sdk/sdk.class.php");  
require_once("library.php");
include_once "place.php";
include_once "info.php";
include_once "address.php";
include_once "contact.php";
include_once "location.php";
include_once "zone.php";
include_once "stringUtil.php";
include_once "cat.php";
include_once "conf_secret.php";
include_once "../LIB/cloudview-sdk-php-clients/papi/PushAPI.inc";



class conf
{
	public $deploy = 'devrenaud'; 
    private $db;
	private $fronturl;
	private $backurl;
	private $thumburl;
	private $bigpath;
	private $originalpath;
	private $originalurl;
	private $bucket;
	private $conf_secret;
	private $filepath;
	
	function __construct(){
		$this->conf_secret = new conf_secret();
		switch($this->deploy){
			case 'dev':
				$this->db = 'yakwala';
				$this->dbtrack = 'yaktrack';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.batch.yakwala.fr';
				$this->thumburl = 'http://dev.batch.yakwala.fr/BACKEND/thumb/';
				$this->originalurl = 'http://dev.batch.yakwala.fr/BACKEND/original/';
				$this->thumbpath = '/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/YAKREP/BACKEND/big/';
				$this->mediumpath = '/YAKREP/BACKEND/medium/';
				$this->originalpath = '/YAKREP/BACKEND/original/';
				$this->batchthumbpath = '/YAKREP/BACKEND/batchthumb/';
				$this->filepath = '/YAKFRONT/main/public/uploads/files/';
				$this->bucket = 'yak1';
				
			break;
			case 'devrenaud':
				$this->db = 'yakwala';
				$this->dbtrack = 'yaktrack';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.batch.yakwala.fr';
				$this->thumburl = 'http://dev.batch.yakwala.fr/BACKEND/thumb/';
				$this->originalurl = 'http://dev.batch.yakwala.fr/BACKEND/original/';
				$this->thumbpath = '/BACKEND/thumb/';
				$this->bigpath = '/BACKEND/big/';
				$this->mediumpath = '/BACKEND/medium/';
				$this->originalpath = '/BACKEND/original/';
				$this->batchthumbpath = '/BACKEND/batchthumb/';
				$this->filepath = '/YAKFRONT/main/public/uploads/files/';
				$this->bucket = 'yak1';
			break;
			case 'preprod':
				$this->dbtrack = 'yaktrack_preprod';
				$this->db = 'yakwala_preprod';
				$this->fronturl = 'http://labs.yakwala.fr';
				$this->backurl = 'http://batch.yakwala.fr/PREPROD/YAKREP/';
				$this->thumburl = 'http://batch.yakwala.fr/PREPROD/YAKREP/BACKEND/thumb';	
				$this->originalurl = 'http://batch.yakwala.fr/PREPROD/YAKREP/BACKEND/original/';				
				$this->thumbpath = '/PREPROD/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/PREPROD/YAKREP/BACKEND/big/';
				$this->mediumpath = '/PREPROD/YAKREP/BACKEND/medium/';
				$this->originalpath = '/PREPROD/YAKREP/BACKEND/original/';
				$this->batchthumbpath = '/PREPROD/YAKREP/BACKEND/batchthumb/';
				$this->filepath = '/PREPROD/YAKFRONT/main/public/uploads/files/';
				$this->bucket = 'yak2';
			break;
			case 'prod':
				$this->db = 'yakwala';
				$this->dbtrack = 'yaktrack';
				$this->fronturl = 'http://labs.yakwala.fr';
				$this->backurl = 'http://batch.yakwala.fr/PROD/YAKREP/';
				$this->thumburl = 'http://batch.yakwala.fr/PROD/YAKREP/BACKEND/thumb';	
				$this->originalurl = 'http://batch.yakwala.fr/PROD/YAKREP/BACKEND/original/';				
				$this->thumbpath = '/PROD/YAKREP/BACKEND/thumb/';
				$this->bigpath = '/PROD/YAKREP/BACKEND/big/';
				$this->mediumpath = '/PROD/YAKREP/BACKEND/medium/';
				$this->originalpath = '/PROD/YAKREP/BACKEND/original/';
				$this->batchthumbpath = '/PROD/YAKREP/BACKEND/batchthumb/';
				$this->filepath = '/PREPROD/YAKFRONT/main/public/uploads/files/';
				$this->bucket = 'yak3';
			break;
			
		}
		$m = new Mongo(); 
		$this->mdb = $m->selectDB($this->db());
		
		$mTrack = new Mongo(); 
		$this->mdbTrack = $m->selectDB($this->dbTrack());
		
	}

	public function getDeploy() {
		return $this->deploy;
	}
	
	public function dbTrack() {
        return  $this->dbtrack;
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
	
	public function originalurl() {
        return  $this->originalurl;
    }

	public function filepath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->filepath;
    }
	
	public function thumbpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->thumbpath;
    }

	public function bigpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->bigpath;
    }
	public function mediumpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->mediumpath;
    }
	public function originalpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->originalpath;
    }
	public function batchthumbpath() {
        return  $_SERVER["DOCUMENT_ROOT"] . $this->batchthumbpath;
    }

	public function mdb() {
        return  $this->mdb;
    }
	
	public function mdbTrack() {
        return  $this->mdbTrack;
    }
	
	public function bucket() {
        return  $this->bucket;
    }
	public function conf_secret() {
        return  $this->conf_secret;
    }
	
}
?>