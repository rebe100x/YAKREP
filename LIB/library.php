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
 * */
function getLocationGMap($q,$output = 'PHP',$debug = 0){
	
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(($q))."&sensor=false";
    echo ($debug==1)?'<br>'.$url:"";
    
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result);
    
    if(sizeof($json->results) > 0){
	    $res = $json->results[0]->geometry->location;
	    if($output == 'JSON')    
	        $res = json_encode($res);
	    if($output == 'PHP')    
	        $res = array($res->lat,$res->lng);
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
                case 'II�me':
            $output = '2e arrondissement';
        break;
        
        case 'IIIe':
            case 'IIIeme':
                case 'III�me':
            $output = '3e arrondissement';
        break;
        
        case 'IVe':
            case 'IVeme':
                case 'IV�me':
            $output = '4e arrondissement';
        break;
        
        case 'Ve':
            case 'Veme':
                case 'V�me':
            $output = '5e arrondissement';
        break;
        
        case 'VIe':
            case 'VIeme':
                case 'VI�me':
            $output = '6e arrondissement';
        break;
        
        case 'VIIe':
            case 'VIIeme':
                case 'VII�me':
            $output = '7e arrondissement';
        break;
        
        case 'VIIIe':
            case 'VIIIeme':
                case 'VIII�me':
            $output = '8e arrondissement';
        break;
        
        case 'IXe':
            case 'IXeme':
                case 'IX�me':
            $output = '9e arrondissement';
        break;
        
        case 'Xe':
            case 'Xeme':
                case 'X�me':
            $output = '10e arrondissement';
        break;
        
        case 'XIe':
        	case 'XIeme':
                case 'XI�me':
            $output = '11e arrondissement';
        break;
        
        case 'XIIe':
        	case 'XIIeme':
        		case 'XII�me':
            $output = '12e arrondissement';
        break;
        
        case 'XIIIe':
        	case 'XIIIeme':
        		case 'XIII�me':
            $output = '13e arrondissement';
        break;
        
        case 'XIVe':
        	case 'XIVeme':
        		case 'XIV�me':
            $output = '14e arrondissement';
        break;
        
        case 'XVe':
        	case 'XVeme':
        		case 'XV�me':
            $output = '15e arrondissement';
        break;
        
        case 'XVIe':
            case 'XVIeme':
        	   case 'XVI�me':
            $output = '16e arrondissement';
        break;
        
        case 'XVIIe':
        case 'XVIIeme':
        	case 'XVII�me':
            $output = '17e arrondissement';
        break;
        
        case 'XVIIIe':
        	case 'XVIIIeme':
        		case 'XVIII�me':
        
            $output = '18e arrondissement';
        break;
        
        case 'XIXe':
        	case 'XIXeme':
        		case 'XIX�me':
        
            $output = '19e arrondissement';
        break;
        
        case 'XXe':
        case 'XXeme':
        case 'XX�me':
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
?>