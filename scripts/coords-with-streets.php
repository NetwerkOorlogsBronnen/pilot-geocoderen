<?

$run = true;       // to debug or test, set run to false

include("settings.php");
include("functions.php");



$sql = "select * from terms where type='Street' and lat = 0";
if(!$run){
    $sql .= " order by rand() limit 1";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){
    if($run){
        echo $c($row['term'])->red() . "\n";
    }

    $streetdata = calcStreetCoords($row['identifier']);


    
    $sql = "update terms set `lon` = '" . $streetdata[0] . "',
                             `lat` = '" . $streetdata[1] . "'
                             where id = " . $row['id'];

    if($run){
        $upd = $mysqli->query($sql);
        echo '+ ';
        echo "\n";
    }else{
        echo $sql;
        echo "\n";
    }

    
}

function calcStreetCoords($streetid){

    $url = 'http://www.hicsuntleones.nl/erfgeoproxy/search/?q=' . urlencode($streetid) . '';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    $coords = array();
    foreach ($data['results'][0]['nwb']['geometry']['coordinates'] as $key => $linepart) {
        foreach ($linepart as $coord) {
            $coords[] = $coord;
         }
    }

    $half = floor(count($coords) / 2);
    $centercoord = $coords[$half];

    return $centercoord;
    
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

    $url = 'http://api.geonames.org/getJSON?geonameId=' . $id . '&maxRows=10&username=xxxxxxx&featureClass=P&lang=nl';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    if(isset($data['name'])){
        return $data;
    }else{
        return false;
    }
    

    
    
}

?>