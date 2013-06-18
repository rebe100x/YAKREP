<?php 
ini_set('display_errors',1);

function trimArray($i){
	return trim(str_replace(' ','',ucwords($i)));	
}
/*
 generate random point arround a center

*/
function generatePointArround($lat1,$lon1,$range=1)
{
	$point = new Location();
	
	$brng = deg2rad(rand(0,360));
	$d = rand(1100,10000*$range)/1000;
	$R= 6371;
	$lat1R = deg2rad($lat1);
	$lon1R = deg2rad($lon1);
	$lat2R = asin( sin($lat1R)*cos($d/$R) + cos($lat1R)*sin($d/$R)*cos($brng) );

	$lon2R = $lon1R + atan2(sin($brng)*sin($d/$R)*cos($lat1R), cos($d/$R)-sin($lat1R)*sin($lat2R));

	$lon2 = rad2deg($lon2R);
	$lat2 = rad2deg($lat2R);
	$point->set($lat2,$lon2);
	return $point;
}
/*
	
*/
function isItWatter($lat,$lng) {
	
	$GMAPStaticUrl = "https://maps.googleapis.com/maps/api/staticmap?center=".$lat.",".$lng."&size=40x40&maptype=roadmap&sensor=false&zoom=12&key=AIzaSyAbYNYyPVWQ78bvZIHHR_djLt-FMEfy2wY";	
	//echo $GMAPStaticUrl;
	$chuid = curl_init();
	curl_setopt($chuid, CURLOPT_URL, $GMAPStaticUrl);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = trim(curl_exec($chuid));
	curl_close($chuid);
	$image = imagecreatefromstring($data);
	
	ob_start();
	imagepng($image);
	$contents =  ob_get_contents();
	ob_end_clean();

	echo "<img src='data:image/png;base64,".base64_encode($contents)."' />";
	
	$hexaColor = imagecolorat($image,0,0);
	$color_tran = imagecolorsforindex($image, $hexaColor);
	
	$hexaColor2 = imagecolorat($image,0,1);
	$color_tran2 = imagecolorsforindex($image, $hexaColor2);
	
	$hexaColor3 = imagecolorat($image,0,2);
	$color_tran3 = imagecolorsforindex($image, $hexaColor3);
	
	$red = $color_tran['red'] + $color_tran2['red'] + $color_tran3['red'];
	$green = $color_tran['green'] + $color_tran2['green'] + $color_tran3['green'];
	$blue = $color_tran['blue'] + $color_tran2['blue'] + $color_tran3['blue'];
	
	imagedestroy($image);
	var_dump($red,$green,$blue);
	//int(492) int(570) int(660) 
	if($red == 492 && $green == 570 && $blue == 660)
		return 1;
	else
		return 0;
}


/* load data form url or from file

*/
function getFeedData($feed){	
	if( !empty($feed['linkSource']) && is_array($feed['linkSource']) && !empty($feed['linkSource'][0]) ){
		$res = array();
		$data = array();
		foreach($feed['linkSource'] as $link){
			$chuid = curl_init();
			curl_setopt($chuid, CURLOPT_URL, $link);	
			//curl_setopt($chuid,CURLOPT_FOLLOWLOCATION,TRUE);
			curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);
			$res[] = trim(curl_exec($chuid));
			curl_close($chuid);
		}
		
		if($feed['feedType'] == 'JSON'){
			foreach($res as $r)
				$allData = array_merge($data,object_to_array(json_decode($r)));
				$data = $allData[$feed['rootElement']];
		}
		
		if($feed['feedType'] == 'XML'){
			foreach($res as $r){
				try{
					$rClean = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $r); 
					$tmp = @simplexml_load_string($rClean,'SimpleXMLElement', LIBXML_NOCDATA);
					if($tmp){
						$tmp2 = $tmp->xpath($feed['rootElement']);
						$data = array_merge(json_decode(json_encode((array) $tmp2), 1),$data);
					}
				}catch(Exception $e){
					echo 'Exception reçue : ',  $e->getMessage(), "<br>";
				}
			}
		}
		if($feed['feedType'] == 'CSV'){
			
		}
	}elseif( !empty($feed['fileSource']) && !empty($feed['fileSource'][0]) && is_array($feed['fileSource']) ){
		if (($handle = fopen($feed['fileSource'], "r")) !== FALSE) {
			while (($line = fgetcsv($handle, 10000, ";")) !== FALSE) {
				$data[] = $line;
			}
		}
	}else{
		$data = false;
	}	

	return $data;	
}

/*Build PAPI DOCUMENT*/
function buildPAPIItem($itemArray,$file){
	
	$myPart = new Part(array('content' =>'',
	'directives' =>
	array('mimeHint' => 'text',
		  'encoding' => 'utf-8')
	)
);
$myDoc = new Document(
	array(
		'uri' => $itemArray['outGoingLink'],
	   'parts' => $myPart,
	   'metas' => array(
			//'path' => 'Path/To/Example/1',
			'file_name' => $file,
			'item_title'=>(!empty($itemArray['title'])?$itemArray['title']:''),
			'item_desc'=>(!empty($itemArray['content'])?$itemArray['content']:''),
			'item_address'=>(!empty($itemArray['address'])?$itemArray['address']:''),
			'item_geolocation'=>(!empty($itemArray['geolocation'])?$itemArray['geolocation']:''),
			'item_latitude'=>(!empty($itemArray['latitude'])?$itemArray['latitude']:''),
			'item_longitude'=>(!empty($itemArray['longitude'])?$itemArray['longitude']:''),
			'uri'=>(!empty($itemArray['outGoingLink'])?$itemArray['outGoingLink']:''),
			'publicurl'=>(!empty($itemArray['outGoingLink'])?$itemArray['outGoingLink']:''),
			'image_enclosure'=>(!empty($itemArray['thumb'])?$itemArray['thumb']:''),
			'item_yakcat'=>(!empty($itemArray['yakCats'])?$itemArray['yakCats']:''),
			'item_freetag'=>(!empty($itemArray['freeTag'])?$itemArray['freeTag']:''),
			'item_place'=>(!empty($itemArray['place'])?$itemArray['place']:''),
			'item_eventDate'=>(!empty($itemArray['eventDate'])?$itemArray['eventDate']:''),
			'item_date'=>(!empty($itemArray['pubDate'])?$itemArray['pubDate']:mktime()),
			'item_tel'=>(!empty($itemArray['telephone'])?$itemArray['telephone']:''),
			'item_transportation'=>(!empty($itemArray['transportation'])?$itemArray['transportation']:''),
			'item_web'=>(!empty($itemArray['web'])?$itemArray['web']:''),
			'item_mail'=>(!empty($itemArray['mail'])?$itemArray['mail']:''),
			'item_opening'=>(!empty($itemArray['opening'])?$itemArray['opening']:''),
		)
	   )
 );
 
return $myDoc;
};


/* Build an XML item of an XL feed crawler
*/
function buildXMLItem($itemArray){
	$xml = "";
	if(sizeof($itemArray)>0){	
		$date = new DateTime();
		$xml = "
			<item>
				<title><![CDATA[".(!empty($itemArray['title'])?$itemArray['title']:'')."]]></title>
				<description><![CDATA[".(!empty($itemArray['content'])?$itemArray['content']:'')."]]></description>
				<outGoingLink><![CDATA[".(!empty($itemArray['outGoingLink'])?$itemArray['outGoingLink']:'')."]]></outGoingLink>
				<thumb><![CDATA[".(!empty($itemArray['thumb'])?$itemArray['thumb']:'')."]]></thumb>
				<yakCats><![CDATA[".(!empty($itemArray['yakCats'])?$itemArray['yakCats']:'')."]]></yakCats>
				<freeTag><![CDATA[".(!empty($itemArray['freeTag'])?$itemArray['freeTag']:'')."]]></freeTag>
				<pubDate><![CDATA[".(!empty($itemArray['pubDate'])?$itemArray['pubDate']:$date->format(DateTime::ISO8601))."]]></pubDate>
				<address><![CDATA[".(!empty($itemArray['address'])?$itemArray['address']:'')."]]></address>
				<place><![CDATA[".(!empty($itemArray['place'])?$itemArray['place']:'')."]]></place>
				<geolocation><![CDATA[".((!empty($itemArray['latitude']) && !empty($itemArray['longitude']))? $itemArray['latitude']."#".$itemArray['longitude']:'')."]]></geolocation> 
				<telephone><![CDATA[".(!empty($itemArray['telephone'])?$itemArray['telephone']:'')."]]></telephone>
				<mobile><![CDATA[".(!empty($itemArray['mobile'])?$itemArray['mobile']:'')."]]></mobile>
				<mail><![CDATA[".(!empty($itemArray['mail'])?$itemArray['mail']:'')."]]></mail>
				<transportation><![CDATA[".(!empty($itemArray['transportation'])?$itemArray['transportation']:'')."]]></transportation>
				<web><![CDATA[".(!empty($itemArray['web'])?$itemArray['web']:'')."]]></web>
				<opening><![CDATA[".(!empty($itemArray['opening'])?$itemArray['opening']:'')."]]></opening>
				<eventDate><![CDATA[".(!empty($itemArray['eventDate'])?$itemArray['eventDate']:'')."]]></eventDate>
				
			</item>
		";
	}
		
	return $xml;
}

/*cast object to array going deeply in the object*/
function object_to_array($data){
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}

/* call to webservice to get and store a preview of the link 
 * return true if success
 * */
function getTeleportImg($spec){
	require_once('teleportd.class.php');
	$apiKey = "2a4f8afd5f3a95d73a53a9076910dba4";
	$tl = new Teleportd($apiKey);
	$fullpath = "thumb/".md5(mktime()).'.jpg';
	$json= $tl->search($spec);

    return $json;
}


/* call to webservice to get and store a preview of the link 
 * return true if success
 * */
function getApercite($link,$conf,$iter=0){

	if( preg_match( "[\/usr\/share\/nginx\/html\/]",$conf->batchthumbpath()) )
		$docroot = "";
	else
		$docroot = "/usr/share/nginx/html/";

		
	$iter++;
	echo '<br> Call Apercite';
	$imgName = md5($link).'.jpg';
	//$fullpath = "thumb/".$imgName;
	$fullpath = $docroot.$conf->thumbpath().$imgName;
	$img = "http://www.apercite.fr/api/apercite/120x90/oui/oui/".$link;	
	echo 'img= '.$img;
	$ch = curl_init ($img);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $rawdata=curl_exec($ch);
    curl_close ($ch);
    
	// check if the image is not in creation process with apercite
	
	
	$creationImgSignature = md5(file_get_contents($docroot.$conf->batchthumbpath().'120x90.jpg'));
	$infoImgSignature = md5($rawdata);
	if ($creationImgSignature == $infoImgSignature && $iter < 	10 ) {
		echo '<br>IMG UNDER CREATION';
		sleep(20);
		getApercite($link,$conf,1);
	}else{
		
		
		if(file_exists($fullpath)){
			unlink($fullpath);
		}
		$fp = fopen($fullpath,'x');
		fwrite($fp, $rawdata);
		fclose($fp);
		
		$s3 = new AmazonS3();
		if(file_exists($fullpath)){
			$response = $s3->create_object($conf->bucket(), '120_90/'.$imgName, array(
				'fileUpload'  => $fullpath,
			   'contentType' => 'image/jpeg',
				'acl'   =>  AmazonS3::ACL_PUBLIC
			));
		}
		
		if($response->status==200 ){
			unlink($fullpath);
		}
	
	}	
	
    return $imgName;
	
}


function createImgThumb($link,$conf){

	// get the file
	$hash = md5($link);
	$res = '';
	//echo $link;

	if( preg_match( "[\/usr\/share\/nginx\/html\/]",$conf->batchthumbpath()) )
		$docroot = "";
	else
		$docroot = "/usr/share/nginx/html/";
		
	$filePathDestOriginal = $docroot.$conf->originalpath() .$hash.'.jpg';
	$filePathDestThumb = $docroot.$conf->thumbpath() .$hash.'.jpg';
	$filePathDestMedium = $docroot.$conf->mediumpath() .$hash.'.jpg';
	$filePathDestBig = $docroot.$conf->bigpath() .$hash.'.jpg';
	$ch = curl_init ($link);
	
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $rawdata=curl_exec($ch);
    curl_close ($ch);
   
	if(file_exists($filePathDestOriginal)){
        @unlink($filePathDestOriginal);
    }
	$fp = fopen($filePathDestOriginal,'x');
    fwrite($fp, $rawdata);
    fclose($fp);
	// create thumb and full size
	
	$res1 = redimg(array(0=>array('W'=>120,'H'=>90)),$filePathDestThumb,$filePathDestOriginal,0);
	$res2 = redimg(array(0=>array('W'=>256,'H'=>0)),$filePathDestMedium,$filePathDestOriginal,0);   
	$res3 = redimg(array(0=>array('W'=>512,'H'=>0)),$filePathDestBig,$filePathDestOriginal,0);   
	
	require_once("aws-sdk/sdk.class.php");
	$s3 = new AmazonS3();
	if(file_exists($filePathDestThumb)){
		$response1 = $s3->create_object($conf->bucket(), '120_90/'.$hash.'.jpg', array(
			'fileUpload'  => $filePathDestThumb,
		   'contentType' => 'image/jpeg',
			'acl'   =>  AmazonS3::ACL_PUBLIC
		));
	}

	if(file_exists($filePathDestMedium)){	
		$response2 = $s3->create_object($conf->bucket(), '256_0/'.$hash.'.jpg', array(
			'fileUpload'  => $filePathDestMedium,
		   'contentType' => 'image/jpeg',
			'acl'   =>  AmazonS3::ACL_PUBLIC
		));
	}

	if(file_exists($filePathDestBig)){	
		$response3 = $s3->create_object($conf->bucket(), '512_0/'.$hash.'.jpg', array(
			'fileUpload'  => $filePathDestBig,
		   'contentType' => 'image/jpeg',
			'acl'   =>  AmazonS3::ACL_PUBLIC
		));
	}
	
	if($res1 && $res2 && $res3)
		$res = $hash.'.jpg';
		
	// NOTE :  your local server has to be on time to send images to S3	
	//var_dump($response1);
	//var_dump($response2);	
	//var_dump($response3);	
	
	if($response1->status==200 && $response2->status==200 && $response3->status==200 ){
		unlink($filePathDestOriginal);
	}
	if($response1->status==200 ){
		unlink($filePathDestThumb);
	}
	if($response2->status==200){
		unlink($filePathDestMedium);
	}
	if($response3->status==200 ){
		unlink($filePathDestBig);
	}
	
	
    return $res;
}


  /*
     * redim une img en plusieurs tailles
     * filePathSrc : le path source : ../data/img.gif
     * arrayDataOutput : array(array('W','H')) si un param est null, on redim avec l'autre en gardant le ratio, si les 2 sont fournis on force le ratio
     * 
     * */
    function redimg($arrayDataOutput,$filePathDest,$filePathSrc,$flagClean=0){
		
        
		foreach($arrayDataOutput as $row)
        {
            if( file_exists($filePathSrc) ){
                $data = file_get_contents($filePathSrc);
				if(!empty($data)){	
					$im = imagecreatefromstring($data);
					if($im){
						if( file_exists($filePathDest) )
							@unlink($filePathDest);
					   
					   $x = imagesx($im);
					   $y = imagesy($im);
					
						
							
						
					   $ratioInput = ( $y>0 ) ? $x / $y : 1 ;
					   
					   if( $row['W'] == 0 && $row['H'] == 0 ){
							$width = $x;
							$height = $y;
						}
						else{
							
							if($x < $row['W'] && $y < $row['H']){
								
								$ratioOutput = 1;
								$height = $row['H'];
								$width = $row['W'];
							}
							
							if( $row['W'] > 0 && $row['H'] > 0){
								$ratioOutput = $row['W'] /$row['H'];
								
								if ($ratioOutput > $ratioInput) {
									$height = $row['H'];
									$width = $ratioInput * $row['H'];
								} else {
									$height = $row['W'] / $ratioInput;
									$width = $row['W'];
								}
							
								if ($height > $row['H']) {
									$height = $row['H'];
								}
								if ($width > $row['W']) {
									$height = $row['W'];
								}
							
							}else{
							   $width = ( $row['W'] > 0 ) ? ($row['W']) : ($row['H'] * $ratioInput);
							   $height = ( $row['H'] > 0 ) ? ($row['H']) : ($row['W'] / $ratioInput); 
							}
						}
					  
						if( $row['W'] > 0 && $row['H'] > 0)
							$copyIm = ImageCreateTrueColor($row['W'],$row['H']);
						else
							 $copyIm = ImageCreateTrueColor($width,$height);
							 
						imagealphablending($copyIm, false);
						imagesavealpha($copyIm, true);  
						imagealphablending($im, true);
						$transparent = imagecolorallocatealpha($copyIm, 255, 255, 255, 127);
						if( $row['W'] > 0 && $row['H'] > 0)
							imagefilledrectangle($copyIm, 0, 0, $row['W'],$row['H'], $transparent);
						else
							imagefilledrectangle($copyIm, 0, 0, $width, $height, $transparent);
							
						if( $row['W'] > 0 && $row['H'] > 0)
							ImageCopyResampled($copyIm,$im,($row['W']-$width)/2, ($row['H']-$height)/2,0,0,$width,$height,$x,$y);
						else
							ImageCopyResampled($copyIm,$im,0,0,0,0,$width,$height,$x,$y);
							
							
						imagejpeg($copyIm,$filePathDest,100);
						chmod($filePathDest,0777);
						$res = 1;
					}else					
						$res = 0;
				}
            }
            else
            {
                $res = 0;
            }
            
        }
        
        //on ne conserve pas l'original
        if($flagClean == 1){
            @unlink($filePathSrc); 
            
        }
		
		
		return $res;
    } 
	
	
	
/* call to gmap
 * retrun a php array(X,Y)
 * ready to go in mongo
 * can output JSON or PHP array
 * We do not take results if approximate ( gmap status )
 * if no location from gmap, status = 10
 * call exemple : $resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.', Paris, France'))),'PHP',1);
 
 * return value : array('status'=>'OK','location'=>array(lat,lng),'address'=>array(street,arr,city,state,area,country,zip),'formatted_address'=>'2 rue des Amandiers')
 * */
function getLocationGMap($q,$output = 'PHP',$debug = 0, $conf=''){
	
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$q."&sensor=false";
    //echo ($debug==1)?'<br>--- URL CALLED : '.$url:"";
    echo '<br>--- URL CALLED : '.$url;
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result);
    //var_dump($json);
    if(sizeof($json->results) > 0){
	    
		$status = $json->results[0]->geometry->location_type;
		echo '<br>status '.$status;
		//if($status != "APPROXIMATE"){
			$res = $json->results[0];
			$address = array("street_number"=>"","street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
			foreach($res->address_components as $itemAddress){
				if(in_array("street_number",$itemAddress->types)) 
					$address['street_number'] = $itemAddress->long_name;
				if(in_array("route",$itemAddress->types) || in_array("transit_station",$itemAddress->types)) 
					$address['street'] = $itemAddress->long_name;
				if(in_array("sublocality",$itemAddress->types))
					$address['arr'] = $itemAddress->long_name;
				if(in_array("locality",$itemAddress->types))
					$address['city'] = $itemAddress->long_name;
				if(in_array("administrative_area_level_2",$itemAddress->types))
					$address['state'] = $itemAddress->long_name;
				if(in_array("administrative_area_level_1",$itemAddress->types))
					$address['area'] = $itemAddress->long_name;
				if(in_array("country",$itemAddress->types))
					$address['country']= $itemAddress->long_name;
				if(in_array("postal_code",$itemAddress->types))
					$address['zip']= $itemAddress->long_name;
					
			}
			
			if($debug==1){
				echo '<br>---ADDRESS---<br>';
				var_dump($address);
			}	
			$location = $json->results[0]->geometry->location;
			
			if($debug==1){
				echo '<br>---LOCATION---<br>';
				var_dump($location);
			}	
			$formatted_address = $json->results[0]->formatted_address;
			
			if($debug==1){
				echo '<br>---FORMATTED ADDRESS---<br>';
				var_dump($formatted_address);
			}	
			$res = array("formatted_address"=>$formatted_address,"address"=>$address,"location"=>array($location->lat,$location->lng),"status"=>$json->status);
			if($output == 'JSON')    
				$res = json_encode($res);
			if($output == 'PHP')    
				$res = $res;
		/*}else{
			$res=0;
		}*/
		
    }else
        $res = 0;
     
	 
    return $res;
    
}

	
/* call to google place api
 * retrun a php array(X,Y)
 * ready to go in mongo
 * can output JSON or PHP array
 * if no location from gmap, status = 10
 
 * return value : array('status'=>'OK','location'=>array(lat,lng),'address'=>array(street,arr,city,state,area,country,zip),'formatted_address'=>'2 rue des Amandiers')
 * */
function getPlaceGMap($q,$output = 'PHP',$debug = 0,$conf=''){
	
	//$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$q."&sensor=false";
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$q."&sensor=false&key=".$conf->conf_secret()->googleAPIKey();
    //echo ($debug==1)?'<br>--- URL CALLED : '.$url:"";
    echo '<br>--- URL CALLED PLACE: '.$url;
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $result = curl_exec($ch);
    curl_close($ch);
	$json = json_decode($result);
    if(sizeof($json->results) > 0){
	    $res = $json->results[0];
		
		$location = $json->results[0]->geometry->location;
		
		if($debug==1){
			echo '<br>---LOCATION---<br>';
			var_dump($location);
		}	
		$formatted_address = $json->results[0]->formatted_address;
		
		if($debug==1){
			echo '<br>---FORMATTED ADDRESS---<br>';
			var_dump($formatted_address);
		}	
		$res = array("formatted_address"=>$formatted_address,"address"=>array(),"location"=>array($location->lat,$location->lng),"status"=>$json->status);
		if($output == 'JSON')    
			$res = json_encode($res);
		if($output == 'PHP')    
			$res = $res;
	
		
    }else
        $res = 0;
     
	 
    return $res;
    
}
/*test*/
//$res = getLocationGMap('109, rue de M�nilmontant');
//var_dump($res);


/** Rewrite the arrondissements to fit gmap type
 * enter VIe or VIeme or VI�me
 * output 6ème arrondissement
 * 
 * */
function rewriteArrondissement($romanLetters){
	
	$romanLetters = str_replace(array('(',')','arrondissement',',','.','-',' '),'',$romanLetters);
	$output = $romanLetters;
	switch($romanLetters){
		case 'Ier':
			case 'Ie':
				case ' Ier':	
					case ' 1e':
						case ' 1er':
			$output = '1er arrondissement';
		break;
		
        case 'IIe':
            case 'IIeme':
                case 'IIème':
					case '2e':
						case '2eme':
							case '2ème':
							
            $output = '2e arrondissement';
        break;
        
        case 'IIIe':
            case 'IIIeme':
                case 'IIIème':
					case '3e':
						case '3eme':
							case '3ème':
            $output = '3e arrondissement';
        break;
        
        case 'IVe':
            case 'IVeme':
                case 'IVème':
					case '4e':
						case '4eme':
							case '4ème':
            $output = '4e arrondissement';
        break;
        
        case 'Ve':
            case 'Veme':
                case 'Vème':
					case '5e':
						case '5eme':
							case '5ème':
            $output = '5e arrondissement';
        break;
        
        case 'VIe':
            case 'VIeme':
                case 'VIème':
					case '6e':
						case '6eme':
							case '6ème':
            $output = '6e arrondissement';
        break;
        
        case 'VIIe':
            case 'VIIeme':
                case 'VIIème':
					case '7e':
						case '7eme':
							case '7ème':
            $output = '7e arrondissement';
        break;
        
        case 'VIIIe':
            case 'VIIIeme':
                case 'VIIIème':
					case '8e':
						case '8eme':
							case '8ème':
            $output = '8e arrondissement';
        break;
        
        case 'IXe':
            case 'IXeme':
                case 'IXème':
					case '9e':
						case '9eme':
							case '9ème':
            $output = '9e arrondissement';
        break;
        
        case 'Xe':
            case 'Xeme':
                case 'Xème':
					case '10e':
						case '10eme':
							case '10ème':
            $output = '10e arrondissement';
        break;
        
        case 'XIe':
        	case 'XIeme':
                case 'XIème':
					case '11e':
						case '11eme':
							case '11ème':
            $output = '11e arrondissement';
        break;
        
        case 'XIIe':
        	case 'XIIeme':
        		case 'XIIème':
					case '12e':
						case '12eme':
							case '12ème':
            $output = '12e arrondissement';
        break;
        
        case 'XIIIe':
        	case 'XIIIeme':
        		case 'XIIIème':
					case '13e':
						case '13eme':
							case '13ème':
            $output = '13e arrondissement';
        break;
        
        case 'XIVe':
        	case 'XIVeme':
        		case 'XIVème':
					case '14e':
						case '14eme':
							case '14ème':
            $output = '14e arrondissement';
        break;
        
        case 'XVe':
        	case 'XVeme':
        		case 'XVème':
					case '15e':
						case '15eme':
							case '15ème':
            $output = '15e arrondissement';
        break;
        
        case 'XVIe':
            case 'XVIeme':
        	   case 'XVIème':
					case '16e':
						case '16eme':
							case '16ème':
            $output = '16e arrondissement';
        break;
        
        case 'XVIIe':
			case 'XVIIeme':
				case 'XVIIème':
					case '17e':
						case '17eme':
							case '17ème':
            $output = '17e arrondissement';
        break;
        
        case 'XVIIIe':
        	case 'XVIIIeme':
        		case 'XVIIIème':
					case '18e':
						case '18eme':
							case '18ème':
        
            $output = '18e arrondissement';
        break;
        
        case 'XIXe':
        	case 'XIXeme':
        		case 'XIXème':
					case '19e':
						case '19eme':
							case '19ème':
        
            $output = '19e arrondissement';
        break;
        
        case 'XXe':
			case 'XXeme':
				case 'XXème':
					case '20e':
						case '20eme':
							case '20ème':
            $output = '20e arrondissement';
        break;
        
        
	}
	
	return $output;
}

/*test
$res = rewriteArrondissementParis('XXe');
var_dump($res);
*/


/*Clean all accentuated char
 **/

function suppr_accents($str){

  $avant = array('À','Á','Â','Ã','Ä','Å','Ā','Ă','Ą','Ǎ','Ǻ','Æ','Ǽ',
'Ç','Ć','Ĉ','Ċ','Č','Ð','Ď','Đ',
'É','È','Ê','Ë','Ē','Ĕ','Ė','Ę','Ě','Ĝ','Ğ','Ġ','Ģ',
'Ĥ','Ħ','Ì','Í','Î','Ï','Ĩ','Ī','Ĭ','Į','İ','ĺ','ļ','ľ','ŀ','ł','Ǐ','Ĳ','Ĵ','Ķ','Ĺ','Ļ','Ľ','Ŀ','Ł',
'Ń','Ņ','Ň','Ñ','Ò','Ó','Ô','Õ','Ö','Ō','Ŏ','Ő','Ơ','Ǒ','Ø','Ǿ','Œ','Ŕ','Ŗ','Ř',
'Ś','Ŝ','Ş','Š','Ţ','Ť','Ŧ','Ũ','Ù','Ú','Û','Ü','Ū','Ŭ','Ů','Ű','Ų','Ư','Ǔ','Ǖ','Ǘ','Ǚ','Ǜ',
'Ŵ','Ý','Ŷ','Ÿ','Ź','Ż','Ž',
'à','á','â','ã','ä','å','ā','ă','ą','ǎ','ǻ','æ','ǽ','ç','ć','ĉ','ċ','č','ď','đ',
'è','é','ê','ë','ē','ĕ','ė','ę','ě','ĝ','ğ','ġ','ģ','ĥ','ħ',
'ì','í','î','ï','ĩ','ī','ĭ','į','ı','ǐ','ĳ','ĵ','ķ',
'ñ','ń','ņ','ň','ŉ','ò','ó','ô','õ','ö','ō','ŏ','ő','ơ','ǒ','ø','ǿ','œ',
'ŕ','ŗ','ř','ś','ŝ','ş','š','ß','ţ','ť','ŧ',
'ù','ú','û','ü','ũ','ū','ŭ','ů','ű','ų','ǔ','ǖ','ǘ','ǚ','ǜ','ư','ŵ','ý','ÿ','ŷ','ź','ż','ž','ƒ','ſ');
  $apres = array('A','A','A','A','A','A','A','A','A','A','A','AE','AE',
'C','C','C','C','C','D','D','D',
'E','E','E','E','E','E','E','E','E','G','G','G','G',
'H','H','I','I','I','I','I','I','I','I','I','I','I','I','I','I','I','IJ','J','K','L','L','L','L','L',
'N','N','N','N','O','O','O','O','O','O','O','O','O','O','O','O','OE','R','R','R',
'S','S','S','S','T','T','T','U','U','U','U','U','U','U','U','U','U','U','U','U','U','U','U',
'W','Y','Y','Y','Z','Z','Z',
'a','a','a','a','a','a','a','a','a','a','a','ae','ae','c','c','c','c','c','d','d',
'e','e','e','e','e','e','e','e','e','g','g','g','g','h','h',
'i','i','i','i','i','i','i','i','i','i','ij','j','k',
'n','n','n','n','n',
'o','o','o','o','o','o','o','o','o','o','o','o','oe',
'r','r','r','s','s','s','s','s','t','t','t',
'u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','w','y','y','y','z','z','z','f','s');
  return str_replace($avant, $apres, $str);
}

/***/
function getZipCodeFromParisArr($str){
	switch($str){
		case 'Ier':
			$str = '75001';
		break;
		case 'IIe':
			$str = '75002';
        break;
        case 'IIIe':
        	$str = '75003';
        break;
        case 'IVe':
        	$str = '75004';
        break;
        case 'Ve':
        	$str = '75005';
        break;
        case 'VIe':
        	$str = '75006';
        break;
        case 'VIIe':
        	$str = '75007';
        break;
        case 'VIIIe':
        	$str = '75008';
        break;
        case 'IXe':
        	$str = '75009';
        break;
        case 'Xe':
        	$str = '75010';
        break;
        case 'XIe':
        	$str = '75011';
        break;
        case 'XIIe':
        	$str = '75012';
        break;
        case 'XIIIe':
        	$str = '75013';
        break;
        case 'XIVe':
        	$str = '75014';
        break;
        case 'XVe':
        	$str = '75014';
        break;
        case 'XVIe':
        	$str = '75016';
        break;
        case 'XVIIe':
        	$str = '75017';
        break;
        case 'XVIIIe':
        	$str = '75018';
        break;
        case 'XIXe':
        	$str = '75019';
        break;
        case 'XXe':
        	$str = '75020';
        break;
	}
	return $str;
	
}

/**
 * Normalize YAKCAT and TAGS
 * Create the pathN by uppercase , suppr accents, and special char
 * @param string
 * @param toUpper : if 1 perform an upper case at the end, default : 1
 * @return string normilized
 */
function yakcatPathN($string,$toUpper=1) { 
	$res=""; 
			
	//pattern array 
	$search=array(		 
	"[Ç|¢|ç]", 
	"[ü|û|ù|Ü|ú|Ú|Û|Ù]", 
	"[é|ê|ë|è|É|Ê|Ë|È]", 
	"[â|ä|à|å|Ä|Å|á|Á|Â|À|ã|Ã]", 
	"[ï|î|ì|í|Í|Î|Ï|Ì]", 
	"[ô|ö|ò|Ö|ø|Ø|ó|Ó|Ô|Ò|õ|Õ]", 
	"[ÿ|ý|Ý]", 
	"[ñ|Ñ]", 
	"[Ð]" 
	); 
	
	//replacement array 
	$replace=array(
	"c", 
	"u", 
	"e", 
	"a", 
	"i", 
	"o", 
	"y", 
	"n", 
	"d" 
	); 

	$string = preg_replace($search,$replace,$string);       //remplacement 
	$string = trim($string); 
	$string = preg_replace("/039/i","",$string); 
	$string = preg_replace( "/[^a-z0-9]/i", "", $string );
	if($toUpper)
		$string = strtoupper($string); 
	
	
	
	return $string; 
}


function to_camel_case($str, $capitalise_first_char = false) {
	if($capitalise_first_char) {
		$str[0] = strtoupper($str[0]);
	}
	$func = create_function('$c', 'return strtoupper($c[1]);');
	return trim(preg_replace_callback('/[_-\s]([a-z])/', $func, $str));
}

function indexForOntology($str)
{            

   $str = str_replace("&","&#x26;",$str); 
   $str = str_replace("\"","&#x22;",$str); 
   
   $search=array(
	 "[é|É]",
	 "[ê|Ê]",
	 "[ë|Ë]",
	 "[è|È]",
	 "[Ç|¢|ç]",
	 "[ü|Ü]",
	 "[û|Û]",
	 "[ù|Ù]",
	 "[ú|Ú]",
	 "[â|Â]",
	 "[ä|Ä]",
	 "[à|À]",
	 "[å|Å|á|Á|ã|Ã]",
	 "[ï|Ï]",
	 "[î|Î]",
	 "[ì|Ì|í|Í]",
	 "[ô|Ô]",
	 "[ö|Ö]",
	 "[ò|Ò|ø|Ø|ó|Ó|õ|Õ]",
	 "[ý|Ý]",
	 "[ÿ]",
	 "[ñ|Ñ]",
	 "[Ð]",
	 "[\']",
	 "[\.]"
	 );

	//replacement array
	$replace=array(
		"&#xe9;",
		"&#xea;",
		"&#xeb;",
		"&#xe8;",
		"&#xe7;",
		"&#xfc;",
		"&#xfb;",
		"&#xf9;",
		"&#xfa;",
		"&#xe2;",
		"&#xe4;",
		"&#xe0;",
		"a",
		"&#xef;",
		"&#xee;",
		"&#xec;",
		"&#xf4;",
		"&#xf6;",
		"o",
		"y",
		"&#xff;",
		"n",
		"d",
		"&#x27;",
		"&#x2e;"
	);

	
	$str = preg_replace($search,$replace,$str); 
	
	return $str;
}

/* generate a random position arround a point

*/
function randomPositionArround($loc){
	$lat1 = $loc['lat'];
	$lon1 = $loc['lng'];
	$brng = deg2rad(rand(0,360));
	$d = rand(1100,10000)/1000000;
	$R= 6371;
	$lat1R = deg2rad($lat1);
	$lon1R = deg2rad($lon1);
	$lat2R = asin( sin($lat1R)*cos($d/$R) + cos($lat1R)*sin($d/$R)*cos($brng) );

	$lon2R = $lon1R + atan2(sin($brng)*sin($d/$R)*cos($lat1R), cos($d/$R)-sin($lat1R)*sin($lat2R));

	$lon2 = rad2deg($lon2R);
	$lat2 = rad2deg($lat2R);

	return array('lat'=>$lat2,'lng'=>$lon2);
}

//function to determine an array of keywords in a text, in case of ambiguous category 
function strpos_arr($haystack, $needle) {
if(!is_array($needle)) $needle = array($needle);
foreach($needle as $what) {
if(($pos = strpos($haystack, $what))!==false) return $pos;
}
return false;
}

function convert_smart_quotes($string){ 
    $search = array("’", 
                    "‘", 
                    "“", 
                    "”");
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"'); 

    return str_replace($search, $replace, $string); 
}

?>
