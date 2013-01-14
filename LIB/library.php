<?php 
ini_set('display_errors',1);

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
function getApercite($link){
	
	$fullpath = "thumb/".md5($link).'.jpg';
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
	
}


function createImgThumb($link,$conf){

	// get the file
	$hash = md5($link);
	
	//echo $link;

	$filePathDestOriginal = $conf->originalpath() .$hash.'.jpg';
	$filePathDestThumb = $conf->thumbpath() .$hash.'.jpg';
	$filePathDestBig = $conf->bigpath() .$hash.'.jpg';
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
	$res1 = redimg(array(0=>array('W'=>80,'H'=>60)),$filePathDestThumb,$filePathDestOriginal,0);
    $res2 = redimg(array(0=>array('W'=>120,'H'=>90)),$filePathDestThumb,$filePathDestOriginal,0);
	$res3 = redimg(array(0=>array('W'=>512,'H'=>0)),$filePathDestBig,$filePathDestOriginal,0);   
	
	if($res1 && $res2 && $res3)
		$res = $hash.'.jpg';
		
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
            else
            {
                $res = 0;
            }
            
        }
        
        //on ne conserve pas l'original
        if($flagClean == 1){
            @unlink(filePathSrc); 
            
        }
		return $res;
    } 
	
/* call to gmap
 * retrun a php array(X,Y)
 * ready to go in mongo
 * can output JSON or PHP array
 * 
 * if no location from gmap, status = 10
 * call exemple : $resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.', Paris, France'))),'PHP',1);
 
 * return value : array('status'=>'OK','location'=>array(lat,lng),'address'=>array(street,arr,city,state,area,country,zip))
 * */
function getLocationGMap($q,$output = 'PHP',$debug = 0){
	
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
	    //$res = $json->results[0]->geometry->location;
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

function suppr_accents($str)
{
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
	 "[ÿ|ý|Ý]",
	 "[ñ|Ñ]",
	 "[Ð]",
	 "[\']"
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
		"n",
		"d",
		"&#x27;"
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





?>
