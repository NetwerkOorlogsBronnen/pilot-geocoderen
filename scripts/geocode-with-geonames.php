<?

$run = false;       // to debug or test, set run to false

include("settings.php");
include("functions.php");



$sql = "select * from terms where geocoded = 'yes' and gn_geocoded = 'no' and result='none'";
if(!$run){
    $sql .= " limit 1";
}else{
    $sql .= " limit 700,1000";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){

    $term = $row['term'];
    
    if($run){
        echo "\nprocessing " . $term . " ... ";
    }
    
    $term = preg_replace("/[0-9]{4} [A-Z]{2}/", "", $term);   // postal codes, no help for HGC

    $parts = explode(",", $term);                             // HGC only likes 1 liesIn parameter
    if(count($parts)>2){
        $term = trim($parts[0]) . "," . trim($parts[1]);
    }

    if(!preg_match("/[a-z]+/", $term)){                       // latlong most probably
        echo "ll ";
        $parts = explode(",", $term);
        $updsql = "update terms set geocoded = 'yes', 
                                                result = 'one',
                                                lat = " . trim($parts[0]) . ",
                                                lon = " . trim($parts[1]) . ",
                                                type = 'Point' 
                                                where id = " . $row['id'];
        if($run){
            $upd = $mysqli->query($updsql);
        }else{
            echo $updsql . "\n";
        }
    }else{
        
            $gnResult = geocodeWithGeoNames($term);
            //print_r($gnResult);
            $matches = array();

            if($gnResult){

                foreach ($gnResult['geonames'] as $loc) {
                    $commaPos = strpos($term,",");
                    if($commaPos > 0){
                        $withoutBroader = substr($term, 0, $commaPos);
                    }else{
                        $withoutBroader = $term;
                    }
                    
                    if($loc['name']==str_replace(", Indonesië","",$withoutBroader)){
                        $matches[] = $loc;
                    }
                }

            }
            if(count($matches)==0){
                echo "- ";
                $updsql = "update terms set gn_geocoded = 'yes', 
                                                result = 'none'
                                                where id = " . $row['id'];
                if($run){
                    $upd = $mysqli->query($updsql);
                }else{
                    echo $updsql . "\n";
                }
            }elseif(count($matches)==1){
                echo "1 ";
                $loc = $matches[0];
                $updsql = "update terms set gn_geocoded = 'yes', 
                                                result = 'one',
                                                lat = " . $loc['lat'] . ",
                                                lon = " . $loc['lng'] . ",
                                                type = '" . $loc['fcodeName'] . "' ,
                                                normalized = '" . $mysqli->real_escape_string($loc['name']) . "' ,
                                                identifier = 'http://sws.geonames.org/" . $loc['geonameId'] . "/' 
                                                where id = " . $row['id'];
                if($run){
                    $upd = $mysqli->query($updsql);
                }else{
                    echo $updsql . "\n";
                }
            }else{
                echo "++ ";
                $updsql = "update terms set gn_geocoded = 'yes', 
                                                result = 'multiple'
                                                where id = " . $row['id'];
                if($run){
                    $upd = $mysqli->query($updsql);
                }else{
                    echo $updsql . "\n";
                }
            }
            
    }
}


function geocodeWithGeoNames($term){

    $url = 'http://api.geonames.org/searchJSON?q=' . urlencode($term) . '&maxRows=10&username=xxxxxxxxx&isNameRequired=true&featureClass=P&lang=nl';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    if($data['totalResultsCount']==0){
        $url = 'http://api.geonames.org/searchJSON?q=' . urlencode($term) . '&maxRows=10&username=xxxxxxxxx&isNameRequired=true&lang=nl';
        $json = file_get_contents($url);
        $data = json_decode($json,true);
    }

    if($data['totalResultsCount']>0){
        //print_r($data);
        return $data;
    }else{
        return false;
    }
    

    
    
}

?>