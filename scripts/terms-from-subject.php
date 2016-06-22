<?

$run = true;       // to debug or test, set run to false

include("settings.php");
include("functions.php");

/* 
DONE: 
MFORCE-MEDIA
IPNV
DANS
GETUIGENVERHALEN
GVNEVDO01
GVNEVDO03
GVNEVDO02
GVNMUSE01
GVNNIOD02
IIO_LEGER
KITLV
DIMCON
BBNA
BBWO2
DIGCOL
ARCHIEVENWO2
OCLC
*/

/*
TODO:
OORLOGSGRAVEN
(other sets lack subject field)
*/

$sql = "select * from oai_harvest where (coverage = '[]' or coverage = '') and subject <> '' and subject <> '[]'  ";
$sql .= " and provenance IN ('OORLOGSGRAVEN')";
if(!$run){
    $sql .= " order by rand() limit 6";
}
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){

    if(!$run){
        echo "\n\nprocessing " . $row['identifier'] . ", " . $row['provenance'] . ", " . $row['subject'] . " ... \n";
        //echo $row['subject'] . "\n";
    }

    $subject = json_decode($row['subject']);
    $subj = array();
    foreach ($subject as $key => $value) {
        $splitted = explode(";", $value);
        foreach ($splitted as $k => $v) {
            $subj[] = trim($v);
        }
    }


    if($row['provenance']=="IIO_LEGER"){    // set has multiple places in one subj field
        //$subj = explode_iio_leger($subj); 
    }
    
    if($row['provenance']=="IPNV"){         // set often has elements for both 'Indonesië' and 'Nederlands-Indië'
        for ($i=0; $i<count($subj); $i++) {
            $subj[$i] = str_replace("Nederlands Indië", "Nederlands-Indië", $subj[$i]);
        } 
    }

    
    
    //$terms = detect_hierarchy($subj);        // see if we can find some hierarchy within terms
    $terms = array_unique($subj);   
    //$terms = dump_dates($terms);   
    
    foreach($terms as $term){
        

        // term already in db?
        $check = $mysqli->query("select * from terms where term = '" . $mysqli->real_escape_string($term) . "'");
        

        if($check->num_rows){   // yes, get term id
            $termrow = $check->fetch_assoc();
            $termid = $termrow['id'];
            echo ". ";
            if(!$run){
                echo "gevonden term " . $term . "\n";
            }
        }else{                  // no, insert term and get term id
            echo '- ';
            $termid = false;
        }
        
        if($termid){
            // insert link between oorlogsbronnen identifier and term id
            if($run){
                // term-identifier-join already in db?
                $check = $mysqli->query("select * from o_x_t where term_id = " . $termid . " and identifier = '" . $row['identifier'] . "'");
                if($check->num_rows){
                    echo '? ';
                }else{
                    $ins = $mysqli->query("insert into o_x_t (identifier,term_id,collection,found_in) values ( 
                                        '" . $row['identifier'] . "',
                                        " . $termid . ",
                                        '" . $row['provenance'] . "',
                                        'subject'
                                        )");
                    echo '& ';
                }
            }
        }
        
    }
    
}



?>