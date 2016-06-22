<?

$run = false;       // to debug or test, set run to false

include("settings.php");
include("functions.php");

/* 
DONE: 
BBWO2
IPNV
DANS
GETUIGENVERHALEN
OIB
OORLOGSMONUMENTEN
IIO_LEGER
DIMCON
BBNA
DIGCOL
ARCHIEVENWO2
*/

/*
TODO:
(other sets lack coverage field)
*/

$sql = "select * from oai_harvest where coverage <> '[]' and coverage <> ''";
$sql .= " and provenance IN ('ARCHIEVENWO2')";
if(!$run){
    $sql .= " order by rand() limit 5";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){

    if(!$run){
        echo "\n\nprocessing " . $row['identifier'] . ", " . $row['provenance'] . " ... \n";
        echo $row['coverage'] . "\n";
    }

    $cov = json_decode($row['coverage']);

    if($row['provenance']=="IIO_LEGER"){    // set has multiple places in one cov field
        $cov = explode_iio_leger($cov); 
    }
    
    if($row['provenance']=="BBNA"){         // set often has elements for both 'Indonesië' and 'Nederlands-Indië'
        for ($i=0; $i<count($cov); $i++) {
            $cov[$i] = str_replace("Nederlands-Indië", "Indonesië", $cov[$i]);
        }
        $cov = array_unique($cov); 
    }

    if($row['provenance']=="DIGCOL"){       // some very custom stuff in DIGCOL!
        if(strpos($row['is_shown_at'],"beeldbank.noord-hollandsarchief")){
            $flip = array_reverse($cov);
            $cov = array($flip[0] . ", " . $flip[1]);
        }
        if(strpos($row['is_shown_at'],"denbosch")){
            $cov = explode(";", $cov[0]);
            $flip = array_reverse($cov);
            $cov = array($flip[0] . ", " . $flip[1]);
        }
        if(strpos($row['is_shown_at'],"rooynet")){
            $flip = array_reverse($cov);
            $parts = array();
            for($i=0; $i<count($flip); $i++){
                if($flip[$i]!="dorp: Centrum"){
                    $from = array("dorp:","gemeente:","straat:");
                    $parts[] = trim(str_replace($from, "", $flip[$i]));
                }
            }
            if(count($parts)>1){
                $geostring = $parts[0] . ", " . $parts[1];
            }else{
                $geostring = $parts[0];
            }
            $cov = array($geostring);
        }
    } 
    
    $terms = detect_hierarchy($cov);        // see if we can find some hierarchy within terms
    $terms = array_unique($terms);   
    $terms = dump_dates($terms);   
    
    foreach($terms as $term){
        if(!$run){
            echo "processing term " . $term . "\n";
        }

        // term already in db?
        $check = $mysqli->query("select * from terms where term = '" . $mysqli->real_escape_string($term) . "'");
        

        if($check->num_rows){   // yes, get term id
            $termrow = $check->fetch_assoc();
            $termid = $termrow['id'];
            echo ". ";
        }else{                  // no, insert term and get term id
            if($run){
                $ins = $mysqli->query("insert into terms (term,found_in) values ( '" . $mysqli->real_escape_string($term) . "','coverage')");
                $termid = $mysqli->insert_id;
            }
            echo '+ ';
        }
        
        // insert link between oorlogsbronnen identifier and term id
        if($run){
            // term-identifier-join already in db?
            $check = $mysqli->query("select * from o_x_t where term_id = " . $termid . " and identifier = '" . $row['identifier'] . "'");
            if($check->num_rows){
                echo ' -';
            }else{
                $ins = $mysqli->query("insert into o_x_t (identifier,term_id,collection,found_in) values ( 
                                    '" . $row['identifier'] . "',
                                    " . $termid . ",
                                    '" . $row['provenance'] . "',
                                    'coverage'
                                    )");
                echo ' &';
            }
        }
        
    }
    
}



?>