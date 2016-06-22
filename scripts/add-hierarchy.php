<?

$run = false;       // to debug or test, set run to false

include("settings.php");
include("functions.php");



$sql = "select * from terms where result='one' and h_country_name = ''";
if(!$run){
    $sql .= " order by rand() limit 1";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){
    if(!$run){
        echo $c(implode("; ",$row))->red() . "\n";
    }

    $hier = array(  "placename" => "", "placeuri" => "",
                    "municipalityname" => "", "municipalityuri" => "",
                    "provincename" => "", "provinceuri" => "",
                    "countryname" => "", "countryuri" => "");


    // first, get place
    if($row['type']=="Address"){
        $inplace = getContainingPlace($row['identifier']);
        $hier["placeuri"] = $inplace['uri'];
        $hier["placename"] = $inplace['name'];
    }elseif($row['type']=="Place"){
        $hier["placeuri"] = $row['identifier'];
        $hier["placename"] = $row['normalized'];
    }elseif($row['type']=="Point"){
        $inplace = geonames_woonplaats_from_coords($row['lat'],$row['lon']);
        $hier["placeuri"] = $inplace['uri'];
        $hier["placename"] = $inplace['name'];
        // try GeoNames for hierarchy
        if(strlen($inplace['uri'])){
            $gnid = str_replace(array("http://sws.geonames.org/","/"),"",$inplace['uri']);
            $gn = geocodeWithGeoNames($gnid);
            //print_r($gnid);
            echo "gn ";
            $hier['countryname'] = $gn['countryName'];
            $hier['countryuri'] = "http://sws.geonames.org/" . $gn['countryId'] . "/";
            if($gn['countryName']=="Nederland"){
                echo "NL ";
                $hier['provincename'] = $gn['adminName1'];
                $hier['provinceuri'] = "http://sws.geonames.org/" . $gn['adminId1'] . "/";
                $hier['municipalityname'] = $gn['adminName2'];
                $hier['municipalityuri'] = "http://sws.geonames.org/" . $gn['adminId2'] . "/";
            }
        }
    }elseif($row['type']=="Street"){
        $inplace = getContainingPlace($row['identifier']);
        $hier["placeuri"] = $inplace['uri'];
        $hier["placename"] = $inplace['name'];
    }elseif($row['type']=="Country"){
        $hier["countryuri"] = $row['identifier'];
        $hier["countryname"] = $row['term'];
    }


    // now, search province and country in woonplaatsen table
    if($hier['placeuri']!=""){
        $sql = "select * from woonplaatsen where gn_uri = '" . $hier['placeuri'] . "'";
        $res = $mysqli->query($sql);
        if($res->num_rows>1){
            $rij = $res->fetch_assoc();
            $hier['provinceuri'] = $rij['provincie_uri'];
            $hier['provincename'] = $rij['provincie'];
            $hier['municipalityuri'] = $rij['gemeente_uri'];
            $hier['municipalityname'] = $rij['gemeente'];
            $hier['countryuri'] = "http://sws.geonames.org/2750405/";
            $hier['countryname'] = "Nederland";
        }else{
            echo $hier['placename'] . " not in woonplaatsen " ."\n";
        }
    }

    if($hier['countryuri']=="" && strpos($row['identifier'],"geonames")){
        
            // try GeoNames for hierarchy
            $gnid = str_replace(array("http://sws.geonames.org/","/"),"",$row['identifier']);
            $gn = geocodeWithGeoNames($gnid);
            //print_r($gnid);
            echo "gn ";
            $hier['countryname'] = $gn['countryName'];
            $hier['countryuri'] = "http://sws.geonames.org/" . $gn['countryId'] . "/";
            if($gn['countryName']=="Nederland"){
                echo "NL ";
                $hier['provincename'] = $gn['adminName1'];
                $hier['provinceuri'] = "http://sws.geonames.org/" . $gn['adminId1'] . "/";
                $hier['municipalityname'] = $gn['adminName2'];
                $hier['municipalityuri'] = "http://sws.geonames.org/" . $gn['adminId2'] . "/";
            }
           


    }



    foreach ($hier as $k => $v) {
        if($v!=""){
            //echo $k . " = " . $v . "; ";
        }
    }

    $sql = "update terms set `h_place_name` = '" . $mysqli->real_escape_string($hier['placename']) . "',
                             `h_place_uri` = '" . $hier['placeuri'] . "', 
                             `h_municipality_name` = '" . $mysqli->real_escape_string($hier['municipalityname']) . "', 
                             `h_municipality_uri` = '" . $hier['municipalityuri'] . "', 
                             `h_province_name`  = '" . $mysqli->real_escape_string($hier['provincename']) . "', 
                             `h_province_uri` = '" . $hier['provinceuri'] . "', 
                             `h_country_name` = '" . $mysqli->real_escape_string($hier['countryname']) . "', 
                             `h_country_uri` = '" . $hier['countryuri'] . "'
                             where id = " . $row['id'];

    if($run){
        $upd = $mysqli->query($sql);
        echo '+ ';
    }else{
        echo $sql;
        echo "\n";
    }

    
}

function getContainingPlace($identifier){

    $url = 'http://www.hicsuntleones.nl/erfgeoproxy/search/?contains=' . urlencode($identifier) . '&dataset=geonames,bag';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    foreach ($data['results'] as $key => $result) {

        if($result['type']=="hg:Street"){
            //print_r($result);
            return getContainingPlace($result['bag']['id']);
        }elseif($result['type']=="hg:Place"){
            //print_r($result);
            return array(   "name"=>$result['geonames']['name'],
                            "uri"=>$result['geonames']['uri']
                        );
        }
    }

    return false;
    
}


function geonames_woonplaats_from_coords($lat,$lon){

    $json = file_get_contents("https://api.histograph.io/search?q=&type=hg:Place&geometry=false&dataset=bag&intersects={%22type%22:%22Point%22,%22coordinates%22:[" . $lon . "," . $lat . "]}");
    $data = json_decode($json,true);

    //print_r($data);
    if(count($data['features'])>0){
        foreach ($data['features'][0]['properties']['pits'] as $k => $pit) {
            if($pit['dataset']=="geonames"){
                return array("name"=>$pit['name'],"uri"=>$pit['uri']);
            }
        }

    }else{
        return false;
    }
}


function geocodeWithGeoNames($id){

    $url = 'http://api.geonames.org/getJSON?geonameId=' . $id . '&maxRows=10&username=xxxxxx&lang=nl';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    if(isset($data['name'])){
        return $data;
    }else{
        return false;
    }
    

    
    
}

?>