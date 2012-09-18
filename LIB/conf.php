<?php 

class conf
{
    private $db;
	private $fronturl;
	private $backurl;
	private $thumburl;
	

	function __construct($env){
		switch($env){
			case 'dev':
				$this->db = 'yakwala';
				$this->fronturl = 'http://dev.yakwala.fr';
				$this->backurl = 'http://dev.backend.yakwala.fr';
				$this->thumburl = 'http://dev.backend.yakwala.fr/BACKEND/thumb/';
			break;
			case 'preprod':
				$this->db = 'yakwala_preprod';
				$this->fronturl = 'http://preprod.yakwala.fr';
				$this->backurl = 'http://preprod.backend.yakwala.fr';
				$this->thumburl = 'http://preprod.backend.yakwala.fr/BACKEND/thumb/';				
			break;
			case 'prod':
				$this->db = 'yakwala';
				$this->fronturl = 'http://prod.yakwala.fr';
				$this->backurl = 'http://prod.backend.yakwala.fr';
				$this->thumburl = 'http://prod.backend.yakwala.fr/BACKEND/thumb/';
			break;
			
		}
	
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
	
	
}
?>