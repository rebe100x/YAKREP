<?php

require_once("conf.php");

 class Cat{
	

   public $title;
   public $path;
   public $pathN;
   public $level;
   public $thumb;
   // Configuration
 	 public $conf;
 	 public $status;
 	 public $ancestors;
 	 public $ancestorsId;
 	 public $ancestorsId2;
 	 public $parent;
 	 public $parentId;
 	 
 	 
function setAncestors($ancestorTitle, $ancestorPathN) {
  $this->ancestors = $ancestorTitle;
  $this->ancestorsId = $this -> getID_fromPathN($ancestorPathN) ;
}

 function setAncestors2($ancestorTitle, $ancestorPathN, $ancestorPathN2) {
  $this->ancestors = $ancestorTitle;
  $this->ancestorsId = $this -> getID_fromPathN($ancestorPathN) ;
  $this->ancestorsId2 = $this -> getID_fromPathN($ancestorPathN2) ;
}




 function setParent($parentTitle, $parentPathN) {
  $this->parent = $parentTitle;
  $this->parentId = $this -> getID_fromPathN($parentPathN) ;
  
}

 	
 function __construct($title1="",$path1="",$pathN1="",$level1="",$thumb1="", $status1 = 1) {
		$this->conf = new Conf();
			$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
		//$this -> id = $db->yakcat->find(array("pathN" => 'STATISTIQUES'));
	  //$this->path='aa';
	  $this->title = $title1;
	  $this->path = $path1;
	  $this->pathN = $pathN1;
	  $this->level = $level1;
	  $this->thumb = $thumb1;
	  $this->status = $status1;
	  
	  //$db->yakcat->insert(array("title" => $this->title, "path" => $this->path));
		
 	}
 	
 	
 	function getID_fromPathN($pathN) {
 	 // print_r($this);
 	  
 	  	$m = new Mongo(); 
		$db = $m->selectDB($this->conf->db());
 	  
 	$result = $db->yakcat->find(array("pathN" => $pathN));
	$result1 = key(iterator_to_array($result));
	
	$result2 = new MongoId($result1);
//	echo $result2;
	return $result2;
 	  
 	} 
 	
 	function getID_fromTitle($title) {
 	 // print_r($this);
 	  
 	 $m = new Mongo(); 
	 $db = $m->selectDB($this->conf->db());
 	  
 	$result = $db->yakcat->find(array("title" => $title));
	$result1 = key(iterator_to_array($result));
	return $result1;
 	  
 	} 
 	
 	function saveToMongo($level=1) {
   
 	  $m = new Mongo(); 
	  $db = $m->selectDB($this->conf->db());
 	 
	  
	   $record = array('title' => $this->title, 'path'=>$this->path,'pathN' => $this->pathN,'level' => $this->level, 'thumb' =>$this->thumb, "creationDate"	=>new MongoDate(gmmktime()),"lastModifDate" =>	new MongoDate(gmmktime()), 'status' =>$this->status);
	  
	  
	  
 	  switch ($level) {
 	    case 1:
 	   //   print_r($record);
 	       $doublon = $this->checkDoublon();
 	      if ($doublon == 0) return $db->yakcat->insert($record);
 	      else return 0;
 	  
 	    break;
 	     case 2:
 	             $doublon = $this->checkDoublon();
 	             
 	             $record['ancestors'][0] = $this ->ancestors;
 	             $record['ancestorsId'][0] = $this ->ancestorsId; 
 	             $record['parent'] = $this ->parent;
 	             $record['parentId'] = $this ->parentId;
 	               
 	      if ($doublon == 0) return $db->yakcat->insert($record);
 	         else return 0;
 	    
 	    break;
 	     case 3:
 	            $doublon = $this->checkDoublon();
 	             
 	             $record['ancestors'][0] = $this ->ancestors;
 	             $record['ancestorsId'][0] = $this ->ancestorsId;
 	              $record['ancestorsId'][1] = $this ->ancestorsId2;  
 	             $record['parent'] = $this ->parent;
 	             $record['parentId'] = $this ->parentId;
 	               
 	      if ($doublon == 0) return $db->yakcat->insert($record);
 	         else return 0;
 	    
 	    break;
 	   
 	  }
 	 
 	}
 	
 	 	
 	function checkDoublon() {
   
 	  $m = new Mongo(); 
	  $db = $m->selectDB($this->conf->db());
	 	
	  $result = $db->yakcat->find(array("pathN" => $this->pathN));
	  $result1 = key(iterator_to_array($result));
	  
	  if (isset($result1)) return 1;
	  else return 0;
	
	
 	} 	

}
 