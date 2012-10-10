<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$info = $db->info;

$res = $info->ensureIndex(array("location"=>"2d"));
$res = $info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1));
$res = $info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1,"user"=>1,"freeTag"=>1));

// TITLE
$urlTitle = "http://enneagon.org/phrases/1";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$urlTitle);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$result = curl_exec($ch);
curl_close($ch);
if(strlen($result)>100)
	$title = substr(utf8_encode($result),0,100)."...";
else
	$title = utf8_encode($result);
	
// CONTENT
$urlContent = "http://enneagon.org/phrases/3";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$urlContent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$result = curl_exec($ch);
curl_close($ch);
if(strlen($result)>300)
	$content = substr(utf8_encode($result),0,300)."...";
else
	$content = utf8_encode($result);
	
// LOCATION
if(empty($_GET['zone']))
	$zone =1;
else
	$zone = $_GET['zone'];
	
switch($zone){
	case "1":
		$lat1 = 48.851875;
		$lon1 = 2.356374;
	break;
	case "2":
		$lat1 = 43.610787;
		$lon1 = 3.876715;
	break;
	case "3":
		$lat1 = 50.583346;
		$lon1 = 4.900031;
	break;
}
echo 'LAT'.$lat1;
//$brng = deg2rad(96.02167);
$brng = deg2rad(rand(0,360));
$d = rand(1100,10000)/1000;
$R= 6371;
$lat1R = deg2rad($lat1);
$lon1R = deg2rad($lon1);
$lat2R = asin( sin($lat1R)*cos($d/$R) + cos($lat1R)*sin($d/$R)*cos($brng) );

$lon2R = $lon1R + atan2(sin($brng)*sin($d/$R)*cos($lat1R), cos($d/$R)-sin($lat1R)*sin($lat2R));

$lon2 = rad2deg($lon2R);
$lat2 = rad2deg($lat2R);

// WEB LINK

$urlWebsite = "http://www.randomwebsite.com/cgi-bin/random.pl";
$options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => true,    // return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_ENCODING       => "",       // handle all encodings 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
        CURLOPT_TIMEOUT        => 120,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 

	/*
    $ch      = curl_init( $urlWebsite ); 
    curl_setopt_array( $ch, $options ); 
    $contentWeb = curl_exec( $ch ); 
    //$err     = curl_errno( $ch ); 
    //$errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    //$header['errno']   = $err; 
   // $header['errmsg']  = $errmsg; 
    //$header['content'] = $content; 
    
	echo $header["url"];
	$thumb = getApercite($header["url"]);
	*/
	echo "<br>ZONE".$zone."<br>";

	$webArray = array("http://www.lemonde.fr/",
					"http://www.liberation.fr/",
					"http://lefigaro.fr/",
					"http://www.rue89.com/",
					"http://www.lesechos.fr/",
					"http://www.journaldunet.com/",
					"http://www.guardian.co.uk/",
					"http://www.washingtonpost.com/",
					"http://www.thetimes.co.uk/tto/news/",
					"http://www.huffingtonpost.fr/",
					"http://www.mediapart.fr/",
					"http://www.bakchich.info/",
					"http://www.slate.fr/",
					"http://www.20minutes.fr/",
					"http://www.arretsurimages.net/",
					"http://owni.fr/",
					"http://www.agoravox.fr/",
					"http://www.20minutes.fr/actus",
					"http://www.lexpress.fr/",
					"http://www.afp.com/",
					"http://www.monde-diplomatique.fr/");
					$weburl = $webArray[rand(0,sizeof($webArray)-1)];
	$thumb = getApercite($weburl);
// THUMB
	//$thumb = "test";
	/*
	$fullpath = "thumb/".md5($link).'.jpeg';
	$img = "http://www.apercite.fr/api/apercite/120x90/oui/oui/".$link;
	$ch = curl_init ($img);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $rawdata=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($fullpath)){
        unlink($fullpath);
    }
    $fp = fopen($fullpath,'x');
    fwrite($fp, $rawdata);
    fclose($fp);
	
    return $fullpath;
	*/
// TYPE	
$yakType = rand(1,4);

$record = array(
	"title"=> $title,
	"content" => $content,
	"outGoingLink" => $weburl,
	"thumb" => $thumb,
	"origin" => "random generator",
	"access" => 2,
	"licence" => "reserved",
	"heat" => 80,
	"yakCat" => array(),
	"freeTag" => array("yakwala"),
	"yakType" => $yakType,
	"pubDate" => new MongoDate(gmmktime()),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"dateEndPrint" => new MongoDate(gmmktime()),
	"print" => 1,
	"status" => 1,
	"user" => 0,
	"zone" => 1,
	"location" => array('lat'=>$lat2,'lng'=>$lon2),
	"address" => "parc des Expositions de la porte de Versailles", 
	"placeId" => new MongoId("506a9e011d22b3457800001e"),

);


//var_dump($record);

$info->save($record);

$info->ensureIndex(array("location"=>"2d"));
$info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1));
$info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1,"user"=>1,"freeTag"=>1));

echo "<br>SAVE : ".$record['_id'];
echo  $title.' '.$lat2.' '.$lon2;
if(!empty($_GET['cleanRandomInfo']))
	$info->remove(array('origin'=>'random generator'));

echo "<br><br>------------------To remove random data, call : ?cleanRandomInfo=1";	
?>
