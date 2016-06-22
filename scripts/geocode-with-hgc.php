<?

$run = true;       // to debug or test, set run to false

include("settings.php");
include("functions.php");



$sql = "select * from terms where geocoded = 'no'";
if(!$run){
    $sql .= " order by rand() limit 5";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){

    $term = $row['term'];
    
    if(!$run){
        echo "\n\nprocessing " . $term . " ... \n";
    }
    
    $term = preg_replace("/[0-9]{4} [A-Z]{2}/", "", $term);   // postal codes, no help for HGC

    $parts = explode(",", $term);                             // HGC only likes 1 liesIn parameter
    if(count($parts)>2){
        $term = trim($parts[0]) . "," . trim($parts[1]);
    }

    $catcha = false;

    if(!preg_match("/[a-zA-Z]+/", $term)){                     // latlong most probably
        echo "ll ";
        $catcha = true;
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
        $types = array("hg:Country","hg:Place","hg:Province","hg:Municipality","hg:Street","hg:Address");
        foreach ($types as $type) {
            $hgcResult = geocodeWithHGC($term,$type);
            if($hgcResult['found']==1){
                echo "1 ";
                $catcha = true;
                $updsql = "     update terms set geocoded = 'yes', result = 'one',";
                if(isset($hgcResult['geometry']['coordinates'][1])){
                    $updsql .= "lat = " . $hgcResult['geometry']['coordinates'][1] . ",
                                lon = " . $hgcResult['geometry']['coordinates'][0] . ", ";
                }
                $updsql .= "    type = '" . str_replace("hg:","",$hgcResult['type']) . "' ,
                                normalized = '" . $mysqli->real_escape_string($hgcResult['name']) . "' ,
                                identifier = '" . $hgcResult['identifier'] . "' 
                                where id = " . $row['id'];
                if($run){
                    $upd = $mysqli->query($updsql);
                }else{
                    echo $updsql . "\n";
                }
                break;
            }elseif ($hgcResult['found']>1){
                echo "++ ";
                $catcha = true;
                $updsql = "update terms set geocoded = 'yes', 
                                                result = 'multiple'
                                                where id = " . $row['id'];
                if($run){
                    $upd = $mysqli->query($updsql);
                }else{
                    echo $updsql . "\n";
                }
                break;
            }
        }
        
    }

    if(!$catcha){
        echo "- ";
        $updsql = "update terms set geocoded = 'yes', 
                                        result = 'none'
                                        where id = " . $row['id'];
        if($run){
            $upd = $mysqli->query($updsql);
        }else{
            echo $updsql . "\n";
        }
    }
}


function geocodeWithHGC($term,$type){

    $prefsets = array("geonames","tgn","bag","nwb","cshapes");
    
    $url = 'http://www.hicsuntleones.nl/erfgeoproxy/search/?q=' . urlencode($term) . '&dataset=geonames,tgn,bag,nwb&type=' . $type;
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    //print_r($data);

    if($data['num-found-with-datasets']!=1){
        return array("found"=>$data['num-found-with-datasets']);
    }else{
        foreach($prefsets as $set){
            $setdata = $data['results'][0][$set];
            //print_r($setdata);
            if(isset($setdata)){
                isset($setdata['uri']) ? $identifier = $setdata['uri'] : $identifier = $setdata['id'];
                return array(
                            "found"=>$data['num-found-with-datasets'],
                            "identifier"=>$identifier,
                            "name"=>$setdata['name'],
                            "geometry"=>$setdata['geometry'],
                            "type"=>$type
                            );
            }
        } 
    }

    return false;
    
}

?>