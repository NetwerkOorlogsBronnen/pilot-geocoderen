<?


function find_names_in_txt($text,$performRegex = true){
    
    if(preg_match("/[A-Z]{6}/", $text)){
        //$text = ucwords(strtolower($text)); // only to be used with newspaperheadings in UPPERCASE
    }
    $formatted = "\"" . htmlentities($text) . "\""; // python troubled by non-unicode chars, now i htmlentity them here and in de-htmlentity them in python
    $result = exec ( "python postag.py $formatted" );

    $data = json_decode($result,true);

    $terms = array();   
    $chunks = array();   
    $i = 0;
    foreach ($data as $k => $sentence) {
        
        $stripped = substr($sentence,10,-2);
        $parts = explode(" ", $stripped);
        foreach ($parts as $k => $part) {
            $wordcodes = explode("/", $part);
            if(substr($wordcodes[2],0,1)=="B" && $wordcodes[1]=="NNP"){
                $i++;
                $chunks[$i] = $wordcodes[0];
            }elseif(substr($wordcodes[2],0,1)=="I" && $wordcodes[1]=="NNP"){
                $chunks[$i] .= " " . $wordcodes[0];
            }else{
                $i++;
            }
        }
        
    }
    if($performRegex){
        foreach ($chunks as $k => $chunk) {
            if(!preg_match("/[\(\)]/",$chunk)){ // soms staat er rommel in chunk
                $pattern = "/(^| )(?i)(te|uit|in|op|bij|naar|gemeente|stad)(?-i) (de|het|den)?[ ]?" . addslashes($chunk) . "/";
                $notwanted = array("den","Den");
                if(preg_match($pattern, $text) && !in_array($chunk, $notwanted)){
                    $from = array("\xc3\xab","\xc3\xbc","\xc3\xa4");
                    $to = array("ë","ü","ä");
                    $terms[] = trim(str_replace($from, $to, $chunk));
                }
            }
        }
    }else{
        foreach ($chunks as $k => $chunk) {
            $from = array("\xc3\xab","\xc3\xbc","\xc3\xa4");
            $to = array("ë","ü","ä");
            $terms[] = trim(str_replace($from, $to, $chunk));
        }
    }
    return $terms;
}






function detect_hierarchy($terms){
    $provs = array(
                "Groningen (provincie)",
                "Friesland",
                "Drenthe",
                "Overijssel",
                "Gelderland",
                "Utrecht (provincie)",
                "Noord-Holland",
                "Zuid-Holland",
                "Noord-Brabant",
                "Zeeland",
                "Limburg",
                "Flevoland"
                );
    $countries = array(
                'Afghanistan',
                'Åland',
                'Albanië',
                'Algerije',
                'Amerikaans-Samoa',
                'Amerikaanse Maagdeneilanden',
                'Andorra',
                'Angola',
                'Anguilla',
                'Antigua en Barbuda',
                'Argentinië',
                'Armenië',
                'Aruba',
                'Australië',
                'Azerbeidzjan',
                'Bahama\'s',
                'Bahrein',
                'Bangladesh',
                'Barbados',
                'België',
                'Belize',
                'Benin',
                'Bermuda',
                'Bhutan',
                'Bolivia',
                'Bosnië en Herzegovina',
                'Botswana',
                'Bouveteiland',
                'Brazilië',
                'Brits Indische Oceaanterritorium',
                'Britse Maagdeneilanden',
                'Brunei',
                'Bulgarije',
                'Burkina Faso',
                'Burundi',
                'Cambodja',
                'Canada',
                'Caribisch Nederland',
                'Centraal-Afrikaanse Republiek',
                'Chili',
                'Christmaseiland',
                'Cocoseilanden',
                'Colombia',
                'Comoren',
                'Congo-Brazzaville',
                'Congo-Kinshasa',
                'Cookeilanden',
                'Costa Rica',
                'Cuba',
                'Curaçao',
                'Cyprus',
                'Denemarken',
                'Djibouti',
                'Dominica',
                'Dominicaanse Republiek',
                'Duitsland',
                'Ecuador',
                'Egypte',
                'El Salvador',
                'Equatoriaal-Guinea',
                'Eritrea',
                'Estland',
                'Ethiopië',
                'Faeröer',
                'Falklandeilanden',
                'Fiji',
                'Filipijnen',
                'Finland',
                'Frankrijk',
                'Frans-Guyana',
                'Frans-Polynesië',
                'Franse Zuidelijke en Antarctische Gebieden',
                'Gabon',
                'Gambia',
                'Georgië',
                'Ghana',
                'Gibraltar',
                'Grenada',
                'Griekenland',
                'Groenland',
                'Guadeloupe',
                'Guam',
                'Guatemala',
                'Guernsey',
                'Guinee',
                'Guinee-Bissau',
                'Guyana',
                'Haïti',
                'Heard en McDonaldeilanden',
                'Honduras',
                'Hongarije',
                'Hongkong',
                'Ierland',
                'IJsland',
                'India',
                'Indonesië',
                'Irak',
                'Iran',
                'Israël',
                'Italië',
                'Ivoorkust',
                'Jamaica',
                'Japan',
                'Jemen',
                'Jersey',
                'Joegoslavië',
                'Jordanië',
                'Kaaimaneilanden',
                'Kaapverdië',
                'Kameroen',
                'Kazachstan',
                'Kenia',
                'Kirgizië',
                'Kiribati',
                'Kleine afgelegen eilanden van de Verenigde Staten',
                'Koeweit',
                'Kroatië',
                'Laos',
                'Lesotho',
                'Letland',
                'Libanon',
                'Liberia',
                'Libië',
                'Liechtenstein',
                'Litouwen',
                'Luxemburg',
                'Macau',
                'Macedonië',
                'Madagaskar',
                'Malawi',
                'Maldiven',
                'Maleisië',
                'Mali',
                'Malta',
                'Man',
                'Marokko',
                'Marshalleilanden',
                'Martinique',
                'Mauritanië',
                'Mauritius',
                'Mayotte',
                'Mexico',
                'Micronesia',
                'Moldavië',
                'Monaco',
                'Mongolië',
                'Montenegro',
                'Montserrat',
                'Mozambique',
                'Myanmar',
                'Namibië',
                'Nauru',
                'Nederland',
                'Nepal',
                'Nicaragua',
                'Nieuw-Caledonië',
                'Nieuw-Zeeland',
                'Niger',
                'Nigeria',
                'Niue',
                'Noord-Korea',
                'Noordelijke Marianen',
                'Noorwegen',
                'Norfolk',
                'Oeganda',
                'Oekraïne',
                'Oezbekistan',
                'Oman',
                'Oost-Timor',
                'Oostenrijk',
                'Pakistan',
                'Palau',
                'Palestina',
                'Panama',
                'Papoea-Nieuw-Guinea',
                'Paraguay',
                'Peru',
                'Pitcairneilanden',
                'Polen',
                'Portugal',
                'Puerto Rico',
                'Qatar',
                'Réunion',
                'Roemenië',
                'Rusland',
                'Rwanda',
                'Saint Kitts en Nevis',
                'Saint Lucia',
                'Saint Vincent en de Grenadines',
                'Saint-Barthélemy',
                'Saint-Pierre en Miquelon',
                'Salomonseilanden',
                'Samoa',
                'San Marino',
                'Sao Tomé en Principe',
                'Saoedi-Arabië',
                'Senegal',
                'Servië',
                'Seychellen',
                'Sierra Leone',
                'Singapore',
                'Sint Maarten',
                'Sint-Helena, Ascension en Tristan da Cunha',
                'Sint-Maarten',
                'Slovenië',
                'Slowakije',
                'Soedan',
                'Somalië',
                'Spanje',
                'Spitsbergen en Jan Mayen',
                'Sri Lanka',
                'Suriname',
                'Swaziland',
                'Syrië',
                'Tadzjikistan',
                'Taiwan',
                'Tanzania',
                'Thailand',
                'Togo',
                'Tokelau-eilanden',
                'Tonga',
                'Trinidad en Tobago',
                'Tsjaad',
                'Tsjechië',
                'Tunesië',
                'Turkije',
                'Turkmenistan',
                'Turks- en Caicoseilanden',
                'Tuvalu',
                'Uruguay',
                'Vanuatu',
                'Vaticaanstad',
                'Venezuela',
                'Verenigd Koninkrijk',
                'Verenigde Arabische Emiraten',
                'Verenigde Staten van Amerika',
                'Verenigde Staten',
                'Vietnam',
                'Volksrepubliek China',
                'Wallis en Futuna',
                'Westelijke Sahara',
                'Wit-Rusland',
                'Zambia',
                'Zimbabwe',
                'Zuid-Afrika',
                'Zuid-Georgia en de Zuidelijke Sandwicheilanden',
                'Zuid-Korea',
                'Zuid-Soedan',
                'zuidpool',
                'Zweden',
                'Zwitserland',
                'Nederlands-Indië', // historical, to be translated to 'Indonesia'
                'Nederlands-Indië',
                'Groot-Brittannië', // name variant, to be translated to 'Verenigd Koninkrijk'
                'Sovjet Unie'       // historical
                );
    
    $indoparts = array(
                'Celebes',
                'Java',
                'Nieuw-Guinea',
                'Sumatra',
                'Molukken',
                'Borneo',
                'Timor'
                );
    $place = "";
    $province = "";
    $country = "";

    // if 3 terms with 'Nederland' one of them, see if we can make a 'placename, province' string
    if(count($terms)==3 && in_array("Nederland",$terms)){
        foreach ($terms as $term) {
            if($term == "Nederland"){
                $country = $term;
            }elseif(in_array($term,$provs)){
                $province = trim(str_replace("(provincie)","",$term));
            }else{
                $place = $term;
            }
        }
        if($place!="" && $province!=""){
            return array($place . ", " . $province);
        }
    }

    // if 3 terms with 'Nederlands-Indië' one of them, see if we can make a 'placename, indopart' string
    if(count($terms)==3 && (in_array("Nederlands-Indië",$terms)||in_array("Indonesië",$terms)) ){
        foreach ($terms as $term) {
            if(in_array($term,$indoparts)){
                $part = trim($term);
            }else{
                $place = $term;
            }
        }
        if($place!="" && $part!=""){
            return array($place . ", " . $part);
        }
    }

    // if 2 terms and one of them is a country, make a 'placename, countryname' string
    if(count($terms)==2){
        foreach ($terms as $term) {
            if(in_array($term,$countries)){
                $country = trim($term);
            }else{
                $place = $term;
            }
        }
        if($place!="" && $country!=""){
            return array($place . ", " . $country);
        }
    }

    // if 2 terms and one of them is a province, make a 'placename, province' string
    if(count($terms)==2){
        foreach ($terms as $term) {
            if(in_array($term,$provs)){
                $province = trim($term);
            }else{
                $place = $term;
            }
        }
        if($place!="" && $province!=""){
            return array($place . ", " . $province);
        }
    }

    return $terms;
}

function dump_dates($terms){
    $properterms = array();
    foreach ($terms as $term) {
        if(!preg_match("/[0-9]{4}/", $term)){
            $properterms[] = $term;
        }elseif(preg_match("/[0-9]{4} [A-Z]{2}/", $term)){ // address with postal code, probably
            $properterms[] = $term;
        }elseif(preg_match("/\.[0-9]{4}/", $term)){ // probably lat or long
            $properterms[] = $term;
        }
    }
    return $properterms;
}

function explode_iio_leger($cov){
    $properterms = array();
    $notwanted = array('Indonesië','Nederlands-Indië');
    foreach($cov as $field){ // didn't see one, but $cov might contain multiple values
        if(strpos($field,"Indonesië")!==false || strpos($field,"Nederlands-Indië")!==false){
            $parts = explode(",", $field);
            foreach ($parts as $part) {
                if(!in_array(trim($part),$notwanted)){
                    $properterms[] = trim($part) . ", Indonesië";
                }
            }
            if(count($properterms)==0){
                $properterms[] = "Indonesië";   // sometimes it's just the country, no places
            }
        }
    }
    return $properterms;
}

?>