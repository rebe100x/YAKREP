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

if(!empty($_GET['cleanRandomInfo'])){
	$info->remove(array('origin'=>'random generator'));
	exit;
}

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
		$range = 1;
	break;
	case "2":
		$lat1 = 43.610787;
		$lon1 = 3.876715;
		$range = 1;
	break;
	case "3":
		$lat1 = 50.583346;
		$lon1 = 4.900031;
		$range = 1;
	break;
	case "14":
		$lat1 = 43.298698;
		$lon1 = 5.370941;
		$range = 10;
	break;
}

$point = generatePointArround($lat1,$lon1,$range);

$flag = isItWatter($point->lat,$point->lng);
if($flag==1)
		echo '<br>IN WATTER';
	else
		echo '<br>IN LAND';
$i=0;
while($flag == 1){
	$tmpPoint = generatePointArround($lat1,$lon1,$range); 
	$point->set($tmpPoint->lat,$tmpPoint->lng);
	$flag = isItWatter($point->lat,$point->lng);
	if($flag==1)
		echo '<br>IN WATTER';
	else
		echo '<br>IN LAND';
	if($i> 10)
		return;
	else
		$i++;
}
	
// WEB LINK

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

// TYPE	
//$yakType = rand(1,4);
$yakType = 4;

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
	"yakCatName" => array(),
	"freeTag" => array("yakwala"),
	"yakType" => $yakType,
	"pubDate" => new MongoDate(gmmktime()),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"dateEndPrint" => new MongoDate(gmmktime()+3*60*60),
	"print" => 1,
	"status" => 1,
	"user" => 0,
	"zone" => 1,
	"location" => array('lat'=>$point->lat,'lng'=>$point->lng),
	"address" => "", 
	"placeId" => new MongoId("506a9e011d22b3457800001e"),

);


//var_dump($record);

$info->save($record);

$info->ensureIndex(array("location"=>"2d"));
$info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1));
$info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1,"user"=>1,"freeTag"=>1));

echo "<br>SAVE : ".$record['_id'];
echo  $title.' '.$point->lat.' , '.$point->lng;

echo "<br><br>------------------To remove random data, call : ?cleanRandomInfo=1";	
?>
