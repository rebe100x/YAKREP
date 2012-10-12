<?php 
ini_set('display_errors',1);

/* call to webservice to get and store a preview of the link 
 * return true if success
 * */
function getApercite($link){
	
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
    echo ($debug==1)?'<br>--- URL CALLED : '.$url:"";
    
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result);
    var_dump($json);
    
	if(sizeof($json->results) > 0){
	    //$res = $json->results[0]->geometry->location;
		$res = $json->results[0];
		$address = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
		foreach($res->address_components as $itemAddress){
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


/** Rewrite the arrondissements of Paris to fit gmap type
 * enter VIe or VIeme or VI�me
 * output 6�me arrondissement
 * 
 * */
function rewriteArrondissementParis($romanLetters){
	$output = $romanLetters;

	
	$romanLetters = str_replace(array('(',')','arrondissement',',','.','-',' '),'',$romanLetters);
	
	switch($romanLetters){
		case 'Ier':
		case 'Ie':
		case ' Ier':	
			$output = '1er arrondissement';
		break;
		
        case 'IIe':
            case 'IIeme':
                case 'IIème':
            $output = '2e arrondissement';
        break;
        
        case 'IIIe':
            case 'IIIeme':
                case 'IIIème':
            $output = '3e arrondissement';
        break;
        
        case 'IVe':
            case 'IVeme':
                case 'IVème':
            $output = '4e arrondissement';
        break;
        
        case 'Ve':
            case 'Veme':
                case 'Vème':
            $output = '5e arrondissement';
        break;
        
        case 'VIe':
            case 'VIeme':
                case 'VIème':
            $output = '6e arrondissement';
        break;
        
        case 'VIIe':
            case 'VIIeme':
                case 'VIIème':
            $output = '7e arrondissement';
        break;
        
        case 'VIIIe':
            case 'VIIIeme':
                case 'VIIIème':
            $output = '8e arrondissement';
        break;
        
        case 'IXe':
            case 'IXeme':
                case 'IXème':
            $output = '9e arrondissement';
        break;
        
        case 'Xe':
            case 'Xeme':
                case 'Xème':
            $output = '10e arrondissement';
        break;
        
        case 'XIe':
        	case 'XIeme':
                case 'XIème':
            $output = '11e arrondissement';
        break;
        
        case 'XIIe':
        	case 'XIIeme':
        		case 'XIIème':
            $output = '12e arrondissement';
        break;
        
        case 'XIIIe':
        	case 'XIIIeme':
        		case 'XIIIème':
            $output = '13e arrondissement';
        break;
        
        case 'XIVe':
        	case 'XIVeme':
        		case 'XIVème':
            $output = '14e arrondissement';
        break;
        
        case 'XVe':
        	case 'XVeme':
        		case 'XVème':
            $output = '15e arrondissement';
        break;
        
        case 'XVIe':
            case 'XVIeme':
        	   case 'XVIème':
            $output = '16e arrondissement';
        break;
        
        case 'XVIIe':
        case 'XVIIeme':
        	case 'XVII�ème':
            $output = '17e arrondissement';
        break;
        
        case 'XVIIIe':
        	case 'XVIIIeme':
        		case 'XVIIIème':
        
            $output = '18e arrondissement';
        break;
        
        case 'XIXe':
        	case 'XIXeme':
        		case 'XIXème':
        
            $output = '19e arrondissement';
        break;
        
        case 'XXe':
        case 'XXeme':
        case 'XXème':
            $output = '20e arrondissement';
        break;
        
        default:
        	$output = $romanLetters.' arrondissement';
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


function indexForOntology($str)
{            
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
	 "[Ð]");

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
		"d"
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
