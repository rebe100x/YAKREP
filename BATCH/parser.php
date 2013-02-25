<?php

/* 
	read feeds and generate a formated xml for exalead
*/
require_once("../LIB/conf.php");
$conf = new conf();

/*
include_once("../LIB/arc/ARC2.php");
include_once("../LIB/Graphite.php");
 
$graph = new Graphite();
$uri = "http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=rdf&offset=0&limit=100";
$uri = "http://dev.batch.yakwala.fr/BATCH/input/mp2013_small.rdf";
$uri = "http://data.ordnancesurvey.co.uk/id/postcodeunit/SO171BJ";
$graph->load( $uri );
$var = $graph->resource( $uri )->dump();
var_dump($var);
*/
//$uri = "http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=rdf&offset=0&limit=100";
/*
$uri = "./input/test.xml";
$xml = file_get_contents($uri);
$events = simplexml_load_string($xml);
var_dump($events);exit;
*/



//$test = get_object_vars($prog);
//print_r($test);



$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;

$q = (empty($_GET['q']))?"":$_GET['q']; 

if($q != ''){
	if($q == 'all') // we parse all valid feeds
		$query = array('status'=>1);
	else
		$query = array('name'=>$q,'status'=>1);

	$res = $feedColl->find($query);

	$feeds = iterator_to_array($res);
	//var_dump($feeds);

	function mapIt($i){
		return '/'.$i.'/';
	}

	foreach ($feeds as $feed) {
		$file = $feed['name'].".xml";	
		echo '<br> Process file '.$file;
		if(!empty($feed['feedType'])){
			$canvas = $feed['parsingTemplate'];
			//var_dump($feed);
			$data = getFeedData($feed);
			if(!empty($data)){
				$xml = "";
				$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";
			
				$line = 0;
				foreach($data as $item){
					
					
					if(!empty($canvas['title']) ){ // we don't take empty lines and header
						//var_dump($item);
						$itemArray = array();
					
						if($feed['feedType'] == 'CSV'){
							if($line >= $feed['lineToBegin']){
								foreach($canvas as $key=>$val){
									$thevalue = '';
									if(!empty($val)){
										
										preg_match_all('/(#YKL)(\d+)/', $val, $out);
										$tmp = array();
										foreach($out[2] as $o)
											$tmp[] = $item[$o];
										$thevalue = preg_replace(array_map('mapIt',$out[0]), $tmp, $val);
										
									}else
										$thevalue = '';
										
									$thevalueClean = $thevalue;
									
									if($key == 'freeTag' || $key == 'yakCats'){
										$tmp = explode(',',$thevalueClean);
										$tmp = array_map('trimArray',$tmp);  
										$thevalueClean = implode('#',$tmp);
									}
									if($key == 'longitude' || $key == 'latitude'){
										$thevalueClean = str_replace(',','.',$thevalueClean);
										$thevalueClean = (float)$thevalueClean;
									}
									
									$itemArray[$key] = $thevalueClean;
									
								}
							}
						}
						
						if($feed['feedType'] == 'RSS'){
							//var_dump($item);
							foreach($canvas as $key=>$val){
								$thevalue = '';
								if(!empty($val)){
									$val = str_replace('#YKLcurrent_french_date',date('d m Y'),$val);
									if(strpos($val,'->')){
										preg_match_all('/(#YKL)(\w+->\w+)/', $val, $out);
									}else
										preg_match_all('/(#YKL)(\w+)/', $val, $out);
									
									//var_dump($out);
									$tmp = array();
									$o1 = array();
									foreach($out[2] as $o){
										if(strpos($o,'->')){
											$o1 = explode('->',$o);
											if(!empty($o1[1])){
												if(sizeof($item[$o1[0]])>1)
													$tmp[] = ( !empty($item[$o1[0]]) && !empty($item[$o1[0]][0]['@attributes'][$o1[1]]) )? $item[$o1[0]][0]['@attributes'][$o1[1]] : '';
												else
													$tmp[] = ( !empty($item[$o1[0]]) && !empty($item[$o1[0]]['@attributes'][$o1[1]]) )? $item[$o1[0]]['@attributes'][$o1[1]] : '';
											}else{
												$tmp[] = (empty($item[$o]))?'':$item[$o];
											}
										}else{
											$tmp[] = (empty($item[$o]))?'':$item[$o];
										}
									}
									//var_dump( $o);
									//var_dump( $item);
									$thevalue = @preg_replace(array_map('mapIt',$out[0]), $tmp, $val);
								}else
									$thevalue = '';
									
								$thevalueClean = $thevalue;
								
								if($key == 'freeTag' || $key == 'yakCats'){
									$tmp = explode(',',$thevalue);
									$tmp = array_map('trimArray',$tmp);  
									$thevalueClean = implode('#',$tmp);
								}
								//echo '<br>key:'.$key.' - '.$thevalueClean;
								if($key == 'longitude' || $key == 'latitude'){
									$thevalueClean = str_replace(',','.',$thevalueClean);
									$thevalueClean = (float)$thevalueClean;
								}
								
								$itemArray[$key] = $thevalueClean;
							}
							
							
							
						}
						
						if($feed['feedType'] == 'JSON'){ // not working on progress
							foreach($canvas as $key=>$val){
								$thevalue = '';
								if(!empty($val)){
									if(strpos($val,'->')){
										preg_match_all('/(#YKL)(\w+->\w+)/', $val, $out);
									}else
										preg_match_all('/(#YKL)(\w+)/', $val, $out);
									//var_dump($out);
									$tmp = array();
									$o1 = array();
									foreach($out[2] as $o){
										if(strpos($o,'->')){
											$o1 = explode('->',$o);
											if(!empty($o1[1])){
												$varTmp = ( !empty($item[$o1[0]]) && !empty($item[$o1[0]][$o1[1]]) )? $item[$o1[0]][$o1[1]] : '';
												if(is_array($varTmp))
													$varTmp2 = $varTmp[0];
												else
													$varTmp2 = $varTmp;
													echo '<br>';var_dump($o1);
												if(!empty($o1[2])){
													$varTmp3 = $varTmp2[$o1[2]];
												}else
													$varTmp3 = $varTmp2;
												//var_dump($varTmp3);	
//												var_dump($item[$o1[0]][$o1[1]][0]['media_url']);
												$tmp[] = $varTmp3;
											}else
												$tmp[] = (empty($item[$o]))?'':(string)$item[$o];
										}else
											$tmp[] = (empty($item[$o]))?'':(string)$item[$o];
									}
									//var_dump( $o);
									//var_dump( $item);
									$thevalue = @preg_replace(array_map('mapIt',$out[0]), $tmp, $val);
									
								}else
									$thevalue = '';
									
								$thevalueClean = $thevalue;
								
								if($key == 'freeTag' || $key == 'yakCats'){
									$tmp = explode(',',$thevalue);
									$tmp = array_map('trimArray',$tmp);  
									$thevalueClean = implode('#',$tmp);
								}
								//echo '<br>key:'.$key.' - '.$thevalueClean;
								if($key == 'longitude' || $key == 'latitude'){
									$thevalueClean = str_replace(',','.',$thevalueClean);
									$thevalueClean = (float)$thevalueClean;
								}
								
								$itemArray[$key] = $thevalueClean;
							}
						}
						
						//var_dump($itemArray);
						
						$xml .= buildXMLItem($itemArray);
						
					}			
					$line++;
				}
				
				$footer ="</items>";
				// echo or write the file according to env var
				if(substr($conf->deploy,0,3) == 'dev'){
					header("Content-Type: application/rss+xml; charset=utf-8");
					echo  $header.$xml.$footer;
				}else{		
					$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
					fwrite($fh, $header.$xml.$footer);
					fclose($fh);
					echo '<br>File Saved '.$file;
				}
			}else
				echo 'No DATA';
			
			
		}
	}

	
	
}else{
	echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA BATCH</title></head><body>";
	echo "<br><br><hr><b>FEEDS:</b><br>";
	echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=all\">ALL FEEDS</a>";
	$feeds = $feedColl->find()->sort(array('humanName'=>'desc'));
	
	foreach ($feeds as $feed) {
		echo "<br>".(($feed['status']==1)?"ACTIVE":"DISABLED")."--- <a href=\"".$_SERVER['PHP_SELF']."?q=".$feed['name']."\"/>".$feed['name']."</a> " ;
	
	}

}	
?>


