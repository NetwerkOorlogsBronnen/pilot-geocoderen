<?

$run = true;       // to debug or test, set run to false

include("settings.php");
include("functions.php");

/* 
DONE: 
MFORCE-MEDIA
IPNV
GETUIGENVERHALEN
OIB
GVNEVDO01
GVNEVDO03
GVNEVDO02
IIO_ONAFHANKELIJK
GVNMUSE01
GVNNIOD02
RAL
DANS
IIO_LEGER
KITLV
DIMCON
BBNA
BBWO2
DIGCOL
ARCHIEVENWO2 Let Op: ook recs waarbij in coverage provincie is opgenomen zijn de moeite waard!
OCLC
ARCHIEVEN
*/

/*
TODO:
NATIONAAL-ARCHIEF
OORLOGSGRAVEN
*/

$sql = "select * from oai_harvest where (coverage = '[]' or coverage = '')";
$sql .= " and provenance IN ('NATIONAAL-ARCHIEF') order by id ASC";
if(!$run){
    $sql .= " order by rand() limit 6";
}
echo $sql . "\n";

$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()){

    if(!$run){
        echo "\n\nprocessing " . $row['identifier'] . " ... \n";
        echo $row['title'] . "\n";
        echo $row['description'] . "\n";
    }

    $placesnamesInTitle = find_names_in_txt($row['title'],true);
    if(!$run){
        foreach($placesnamesInTitle as $name){
            echo $c($name)->yellow() . "\n";
        }
    }
    $placesnamesInDesc = find_names_in_txt($row['description'],true);
    if(!$run){
        foreach($placesnamesInDesc as $name){
            echo $c($name)->magenta() . "\n";
        }
    }

    $terms = array_merge($placesnamesInDesc,$placesnamesInTitle);

    $terms = array_unique($terms);      
    
    foreach($terms as $term){
        if(!$run){
            //echo "processing term " . $term . "\n";
        }

        // term already in db?
        $check = $mysqli->query("select * from terms where term = '" . $mysqli->real_escape_string($term) . "'");
        

        if($check->num_rows){   // yes, get term id
            $termrow = $check->fetch_assoc();
            $termid = $termrow['id'];
            echo ". ";
            if(!$run){
                echo $term . " ";
            }
        }else{                  // no, insert term and get term id
            if($run){
                $ins = $mysqli->query("insert into terms (term,found_in) values ( '" . $mysqli->real_escape_string($term) . "','text')");
                $termid = $mysqli->insert_id;
            }
            echo '+ ';
            if(!$run){
                echo $term . " ";
            }
        }
        
        // insert link between oorlogsbronnen identifier and term id
        if($run){
            // term-identifier-join already in db?
            $check = $mysqli->query("select * from o_x_t where term_id = " . $termid . " and identifier = '" . $row['identifier'] . "'");
            if($check->num_rows){
                echo ' -';
            }else{
                $inTitle = 0;
                $inDescription = 0;
                if(in_array($term,$placesnamesInTitle)){
                    $inTitle = 1;
                }
                if(in_array($term,$placesnamesInDesc)){
                    $inDescription = 1;
                }
                $ins = $mysqli->query("insert into o_x_t (identifier,term_id,collection,found_in,in_title,in_description) values ( 
                                    '" . $row['identifier'] . "',
                                    " . $termid . ",
                                    '" . $row['provenance'] . "',
                                    'text',
                                    " . $inTitle . ",
                                    " . $inDescription . "
                                    )");
            }
        }
        
    }
    
}



?>