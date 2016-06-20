#Eindrapportage Pilot Geocoderen Oorlogsbronnen

De Pilot geocoderen Oorlogsbronnen is uitgevoerd door Menno den Engelse in opdracht van Netwerk Oorlogsbronnen. Projectleider was Annelies van Nispen. De looptijd van de pilot was maart-juni 2016. 

##Doel en opdracht

Het doel van de pilot Geocoderen Oorlogsbronnen was tweeledig:

- Een data-analyse van de huidige geografische metadata in portal oorlogsbronnen. Hoe is de kwaliteit van de metadata en welke (niet)bruikbare resultaten levert geocoderen van de metadata op,
- een goede en praktische wijze vinden om de door Oorlogsbronnen geharveste metadata te geocoderen en te verrijken. Deze wijze moet aansluiten bij de bestaande technische infrastructuur van oorlogsbronnen.
 
Met de resultaten van de pilot moet ‘zoeken op plaats/geografisch zoeken’ verbeterd worden, het moet mogelijk worden  om ‘hiërarchisch’ geografisch te zoeken (plaats/gemeente/provincie). Er moeten sets gemaakt kunnen worden van geografische eenheden en metadata zal ook via de kaart ontsloten kunnen worden..
 
De pilot beperkt zich tot geografische aanduidingen in de metadata die wordt geleverd door de partners van NOB. In de toekomst zullen mogelijk ook de OCR-data kunnen worden gebruikt.

De pilot zal onderzoeken welke geocoders gebruikt kunnen worden voor geocoderen. Op dit moment worden de Historische Geocoder, Geonames en TGN genoemd. TGN en vooral GeoNames zijn veelgebruikte geografische thesauri, de Historische Geocoder bevat meerdere geografische thesauri - naast het Nederlandse deel van GeoNames en TGN bijvoorbeeld ook de Basisregistraties Adressen en Gebouwen (BAG).

In de pilot moet onderzocht worden welke services gebruikt kunnen worden en wat de voor- & nadelen zijn.

###Waar en wat opslaan?
Belangrijk is dat de originele data in originele staat blijft en dat de verrijkingen apart worden opgeslagen. Het onderscheid verrijkt en originele data moet duidelijk blijven.

De verrijkingen zullen bestaan uit identifiers (URI’s), geometrie en hiërarchie. Gekeken moet worden welke thesauri (GeoNames, BAG, TGN, Gemeentegeschiedenis) gebruikt zullen worden.

###Geocoderen inbouwen in infrastructuur

Onderdeel van de pilot is ook te kijken hoe geocoderen in het huidige harvest & publicatie proces van Oorlogsbronnen kan worden ingepast. De technische infrastructuur wordt onderhouden door Trifork. De geografische metadata & georefereren moeten aansluiten bij deze infrastructuur.

Onderzocht is welke stappen in het proces aangepast of toegevoegd moeten worden, dit voor zowel nieuwe als gewijzigde objecten. De context, bijvoorbeeld uit welke collectie het object komt, is belangrijk en moet meegenomen worden.

###NOB op de Kaart
Om nut en noodzaak van het geocoderen te illustreren worden één of meerdere kaartapplicaties gemaakt. Deze kaarten kunnen ook goed gebruikt worden bij het beoordelen en duiden van de resultaten.Het presenteren van bronnen op kaart is een belangrijke uitkomst.
·         
##De data
De data bestaat uit door Netwerk Oorlogsbronnen geaggregeerde metadata van 27 collecties. Dit kunnen zowel museale, archief als bibliotheekcollecties zijn. Twee collecties (DIMCON en DIGCOL) bestaan op hun beurt weer uit verschillende collecties. 

De kwaliteit van de metadata loopt sterk uiteen en dat kan mogelijk de kwaliteit van het geocoderen en geografische toepassingen beïnvloeden. De overgrote meerderheid van de metadata is nu in het Dublin Core formaat. In sommige gevallen beschikken we ook over rijkere metadata als ESE of EAD.

De uitkomst van de data-analyse op een testset moeten de (kwaliteits)problemen in kaart brengen en wat dit betekent voor geocoderen en verrijken. Naast een analyse van de kwaliteit van de metadata en de consequenties hiervan voor de ontsluiting. Daarnaast zal er advies worden gegeven voor praktische oplossingen.
Uitgangspunt van de pilot is dat geografische metadata in de volgende velden kan worden aangetroffen:
titel (dc:title)
beschrijving (dc:description)
dekking/plaats/tijd (dc:coverage)
onderwerp/trefwoorden (dc:subject)

Voor deze velden worden de opbrengsten en kwaliteit geanalyseerd. Ook wordt er per collectie bekeken wat de opbrengst is.

Voor het veld Titel kan eventueel Named Entity Recognition (NER) worden verkend. In coverage worden vaak vele geografische termen, locaties weergegeven. Deze zou mogelijk ook nog kunnen gebruikt voor geografische hierarische relaties (plaats-gemeente-provincie). In trefwoorden zijn soms preciezere locaties opgenomen dan in coverage. Mogelijk bevind zich in andere velden ook nog geografische metadata.


##Werkwijze

###Extractie termen uit coverage

Eerst zijn geografische termen uit `coverage` opgenomen. Meest problematische daarbij was het reconstrueren van hiërarchie. Die was op allerlei logische maar verschillende manieren opgenomen - kommagescheiden in één veld, in verschillende velden, in verschillende volgordes, met verschillende schrijfwijzes voor provincies, met opname gemeente, voorzien van aanduidingen als 'dorp:' of 'gemeente:', met opname streek of eiland of aangeduid met historische naam. Regelmatig kwamen meerdere plaatsaanduidingen bij één record voor, zodat vaak onduidelijk was welke termen tot een hiërarchie behoorden en welke niet.

Vergelijk de volgende voorbeelden - wanneer is er sprake van hiërarchie en hoe vis je die eruit?

> `haarlem`, `alkmaar`, `1930-1945`  
> `haarlem`, `nederland`, `noord-holland`  
> `haarlem`, `noord-holland`, `nederland`  
> `bloemendaal`, `haarlem`, `nederland`, `noord-holland`  
> `nederland`, `noord-holland`, `haarlem`, `haarlem`  
> `haarlem, westerbork, vught, auschwitz`

> `indonesië`, `nederland`  
> `aek rioeng, indonesië, nederlands-indië`  
> `indonesië`, `nederlands-indië`, `pasoeroean`  
> `buitenzorg`, `java`, `kedoengbadak`, `nederlands-indië`  
> `bangkinan`, `nederlands-indië`, `sumatra`
> `botosari, indonesië, nederlands-indië, poerwakarta`

> `gemeente: Venray`,`dorp: Centrum`,`straat: Leeuwstraat` 


###Extractie termen uit tekst
Van records zonder `coverage` zijn `title` en `description` tegen een [postagger](http://www.clips.ua.ac.be/pages/pattern-nl) aangehouden om eigennamen te extraheren. Die eigennamen zijn vervolgens alleen als geografische term opgeslagen als ze door de reguliere expressie `(^| )(?i)(te|uit|in|op|bij|naar)(?-i) (de|het|den)?[ ]?Eigennaam` kwamen (waarbij `Eigennaam` vervangen werd door de door de postagger gevonden eigennaam).

Dit is gedaan met alle datasets behalve de kranten (zie voor de kranten de test met de 190 in de oorlog verschenen nummers van De Gelderlander).

<table>
<tr><td>Totaal aantal records</td><td>800.911</td></tr>
<tr><td>Records met coverage veld</td><td>176.912</td></tr>
<tr><td>Aantal x term gevonden in coverage</td><td>214.667</td></tr>
<tr><td>Aantal x term gevonden in tekstveld</td><td>121.643</td></tr>
<tr><td>Aantal unieke termen</td><td>26.260</td></tr>
</table>


###Geocoderen

De termen zijn eerst tegen de [Historisch Geocoder](http://www.erfgeo.nl) gehouden, waarbij voor het gemak gebruik is gemaakt van de [erfgeoproxy](http://www.hicsuntleones.nl/erfgeoproxy/).

Om de kans op een eenduidig resultaat te vergroten is eerst gezocht naar landen, vervolgens naar plaatsen, provincies, gemeenten, straten en tot slot adressen. Zodra er een resultaat was is steeds het zoeken gestopt, zodat bij Denemarken het land gevonden werd, en niet ook de gelijknamige plaats bij Slochteren.

Er is zoveel mogelijk gegeocodeert naar GeoNames URIs, een keuze die is ingegeven door de wereldwijde dekking, de gebruiksvriendelijke API van GeoNames zelf en de brede toepassing van GeoNames URIs in wetenschap en het erfgoedveld. Voor straten, adressen en gebouwen zijn BAG id's gebruikt. De BAG (Basisadministratie Adressen en Gebouwen) heeft nog geen URIs (googlen op BAG URI brengt je bij plaszakjes in voor dames en heren), maar de BAG id's zijn wel op te vragen bij de Historische Geocoder.

Bij één resultaat is het resultaat opgeslagen, bij meerdere resultaten is de term alleen gemarkeerd met 'multiple'. Dit betekent dat een term meerdere geocodes kan opleveren en het vergt (vaak handmatig) onderzoek om de juiste match te maken. Er ligt een Middelburg op Walcheren, maar ook een Middelburg in Zuid-Holland.

Termen die geen enkel resultaat gaven zijn vervolgens tegen de [GeoNames API](http://www.geonames.org/export/web-services.html) gehouden, waarbij een locatie als resultaat is bestempeld als de schrijfwijze exact overeen kwam. Daarbij moet wel een goede afweging gemaakt worden naar welke talen wel en niet gekeken wordt: ‘Brussel’ matcht niet met ‘Brussels’, maar wel met een Zuid-Afrikaanse boerderij genaamd ‘Brussel’. 

<table>
<tr><td></td><td>HGC</td><td>GeoNames</td><td>Totaal</td></tr>
<tr><td>één resultaat</td><td>9.710</td><td>858</td><td>10.568</td></tr>
<tr><td>meerdere resultaten</td><td>1151</td><td>957</td><td>2.108</td></tr>
<tr><td>geen resultaat</td><td>189</td><td>13.370</td><td>13.584</td></tr>
</table>

De termen met één resultaat zijn onder te verdelen in de onderstaande typen. De typen met hoofdletter (behalve `Point`, waarbij geocoding niet nodig was) komen uit de Historische Geocoder, de overige uit GeoNames.

<table>
<tr><td>count</td><td>type</td></tr>
<tr><td>3400</td><td>Place</td></tr>
<tr><td>3229</td><td>Point (coördinaten in `coverage`)</td></tr>
<tr><td>1938</td><td>Street</td></tr>
<tr><td>852</td><td>Address</td></tr>
<tr><td>454</td><td>populated place</td></tr>
<tr><td>184</td><td>Country</td></tr>
<tr><td>78</td><td>Municipality</td></tr>
<tr><td>451</td><td>other (neighbourhood, hotel, museum, island, etc.)</td></tr>
</table>

###Extractie termen uit subject

Van records zonder `coverage` zijn, pas na het geocoderen, de trefwoorden uit het veld `subject` tegen de in de vorige stappen al geëxtraheerde termen gehouden. Trefwoorden die nog niet in die termen voorkwamen zijn dus niet opgenomen. Enerzijds omdat het tegen verschillende geocoders houden van alle nieuwe termen veel tijd zou kosten, anderzijds omdat de kans op vals positieven groot was.

<table>
<tr><td>Aantal records met term uit subject</td><td>411.565</td></tr>
<tr><td>waarbij term 1 resultaat heeft</td><td>248.169</td></tr>
<tr><td>waarbij term geen resultaat heeft</td><td>208.598</td></tr>
<tr><td>waarbij term meerdere resultaten heeft</td><td>49.791</td></tr>
</table>


###Resultaten en kwaliteit van de geocoderingen

De resultaten verschillen aanzienlijk per dataset, zoals onderstaande tabel laat zien. De 100 procent score van OORLOGSMONUMENTEN torent hoog uit boven de 8 procent van OCLC.

Records met een `coverage` veld scoren naar verwachting aanzienlijk beter dan wanneer we louter op tekst aangewezen zijn. Opvallend is wel dat termen uit `coverage` lang niet altijd eenduidig gegeocodeerd kunnen worden - vergelijk de 120 duizend records met `coverage` veld van BBWO2, waarvan er maar 80 duizend gegeocodeerd zijn.


<table>
<tr>
<tr>
<th>term uit coverage</th>
<th>resultaat uit coverage</th>
<th>idem, % van totaal</th>
<th>term uit text</th>
<th>resultaat uit text</th>
<th>idem, % van totaal</th>
<th>term uit subject</th>
<th>resultaat uit subject</th>
<th>idem, % van totaal</th>
</tr>
<tr>
<td colspan="7">ARCHIEVEN<br />NIOD archieven en collecties</td>
<td colspan="2">151988 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>32653</td>
<td>21735</td>
<td>14 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">ARCHIEVENWO2<br />Archieven WO2</td>
<td colspan="2">48362 records</td>
</tr>
<tr>
<td>2714</td>
<td>2617</td>
<td>5 %</td>
<td>7587</td>
<td>5141</td>
<td>11 %</td>
<td>19072</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">BBNA<br />Beeldbank Nationaal Archief</td>
<td colspan="2">24920 records</td>
</tr>
<tr>
<td>19538</td>
<td>15101</td>
<td>61 %</td>
<td>779</td>
<td>361</td>
<td>1 %</td>
<td>3991</td>
<td>212</td>
<td>1 %</td>
</tr>
<tr>
<td colspan="7">BBWO2<br />Beeldbank WO2</td>
<td colspan="2">132976 records</td>
</tr>
<tr>
<td>119650</td>
<td>99492</td>
<td>75 %</td>
<td>2236</td>
<td>1303</td>
<td>1 %</td>
<td>3601</td>
<td>113</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">DANS<br />DANS-KNAW - diverse collecties</td>
<td colspan="2">240 records</td>
</tr>
<tr>
<td>237</td>
<td>209</td>
<td>87 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>1</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">DIGCOL<br />Digitale collecties</td>
<td colspan="2">33418 records</td>
</tr>
<tr>
<td>1909</td>
<td>1337</td>
<td>4 %</td>
<td>11352</td>
<td>5207</td>
<td>16 %</td>
<td>15045</td>
<td>515</td>
<td>2 %</td>
</tr>
<tr>
<td colspan="7">DIMCON<br />Digitale Museale Collectie Nederland</td>
<td colspan="2">23476 records</td>
</tr>
<tr>
<td>20907</td>
<td>17509</td>
<td>75 %</td>
<td>851</td>
<td>527</td>
<td>2 %</td>
<td>1346</td>
<td>778</td>
<td>3 %</td>
</tr>
<tr>
<td colspan="7">GETUIGENVERHALEN<br />Getuigenverhalen</td>
<td colspan="2">655 records</td>
</tr>
<tr>
<td>587</td>
<td>470</td>
<td>72 %</td>
<td>14</td>
<td>14</td>
<td>2 %</td>
<td>19</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">GVNEVDO01<br />Geheugen van Nederland - Oorlogsdagboeken</td>
<td colspan="2">802 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>416</td>
<td>397</td>
<td>50 %</td>
<td>782</td>
<td>299</td>
<td>37 %</td>
</tr>
<tr>
<td colspan="7">GVNEVDO02<br />Geheugen van Nederland - Propagandadrukwerk WO II</td>
<td colspan="2">2935 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>290</td>
<td>155</td>
<td>5 %</td>
<td>2830</td>
<td>1211</td>
<td>41 %</td>
</tr>
<tr>
<td colspan="7">GVNEVDO03<br />Geheugen van Nederland - Verzetsliteratuur</td>
<td colspan="2">6151 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>234</td>
<td>109</td>
<td>2 %</td>
<td>6109</td>
<td>355</td>
<td>6 %</td>
</tr>
<tr>
<td colspan="7">GVNMUSE01<br />Geheugen van Nederland - Kamptekeningen uit bezet Nederlands-Indië (1942-1945)</td>
<td colspan="2">4938 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>833</td>
<td>218</td>
<td>4 %</td>
<td>4878</td>
<td>3228</td>
<td>65 %</td>
</tr>
<tr>
<td colspan="7">GVNNIOD02<br />Geheugen van Nederland - Illegale pamfletten en brochures</td>
<td colspan="2">1234 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>164</td>
<td>92</td>
<td>7 %</td>
<td>1234</td>
<td>1197</td>
<td>97 %</td>
</tr>
<tr>
<td colspan="7">IIO_LEGER<br />Fotocollectie Dienst voor legercontacten Indonesië</td>
<td colspan="2">7048 records</td>
</tr>
<tr>
<td>7014</td>
<td>3252</td>
<td>46 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">IIO_ONAFHANKELIJK<br />Indonesië onafhankelijk 1947-1951</td>
<td colspan="2">4580 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>239</td>
<td>161</td>
<td>4 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">IPNV<br />Interviewproject Nederlandse Veteranen</td>
<td colspan="2">101 records</td>
</tr>
<tr>
<td>79</td>
<td>78</td>
<td>77 %</td>
<td>22</td>
<td>18</td>
<td>18 %</td>
<td>22</td>
<td>16</td>
<td>16 %</td>
</tr>
<tr>
<td colspan="7">KITLV<br />Beeldbank KITLV</td>
<td colspan="2">15189 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>9270</td>
<td>3576</td>
<td>24 %</td>
<td>15145</td>
<td>15110</td>
<td>99 %</td>
</tr>
<tr>
<td colspan="7">MFORCE-MEDIA<br />WO2 in Muziek</td>
<td colspan="2">72 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>39</td>
<td>25</td>
<td>35 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">NATIONAAL-ARCHIEF<br />Archieven Nationaal Archief</td>
<td colspan="2">76261 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>16576</td>
<td>10555</td>
<td>14 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">OCLC<br />NIOD bibliotheek</td>
<td colspan="2">62157 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>9809</td>
<td>4836</td>
<td>8 %</td>
<td>57157</td>
<td>48303</td>
<td>78 %</td>
</tr>
<tr>
<td colspan="7">OIB<br />Oorlog in Blik</td>
<td colspan="2">742 records</td>
</tr>
<tr>
<td>455</td>
<td>404</td>
<td>54 %</td>
<td>172</td>
<td>156</td>
<td>21 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">OORLOGSGRAVEN<br />Oorlogsgraven</td>
<td colspan="2">169353 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>38158</td>
<td>56</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">OORLOGSMONUMENTEN<br />Oorlogsmonumenten</td>
<td colspan="2">3725 records</td>
</tr>
<tr>
<td>3725</td>
<td>3724</td>
<td>100 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
<tr>
<td colspan="7">RAL<br />Regioneel Archief Leiden</td>
<td colspan="2">29588 records</td>
</tr>
<tr>
<td>0</td>
<td>0</td>
<td>0 %</td>
<td>1751</td>
<td>492</td>
<td>2 %</td>
<td>0</td>
<td>0</td>
<td>0 %</td>
</tr>
</table>


###Vals positieven

Maar hoe goed zijn de resultaten eigenlijk? We hebben een steekproef gedaan van tweehonderd random gekozen uit `coverage` afkomstige termen en zo'n zelfde steekproef voor termen afkomstig uit tekstvelden.

Van die tweehonderd uit `coverage` afkomstige termen was 0.5% verkeerd gegeocodeerd - `Atlantische Oceaan` werd beschouwd als straat in Naaldwijk. Creatieve straatnaamcommissies zorgen overigens voor meer problemen - buiten de steekproef kwamen we in Purmerend een wijkje tegen met straten als `Bali`, `Kalimantan`, `Sulawesi` en `Borneo`.

Bij tweehonderd uit tekst afkomstige termen was zo'n 18.5 % vals positief. Daarbij maakt het wel uit of je naar termen kijkt die alléén in tekst voorkomen (25 van de 100 vals positief) of termen die weliswaar in tekst zijn aangetroffen, maar die soms ook in coverage voorkomen (12 van de 100 vals positief).

Ter illustratie: 'Groothandelaren in Glas' heeft niets te maken met de Oostenrijkse plaats `Glas`, met de 'Lofzang tot God in Sion' had men vast niet het Rijswijkse Sionbuurtje op 't oog en het 'Kriegsschiff in Not' zal niet in het Oostenrijkse `Not` voor anker hebben gelegen. 

Ook hier nemen straatnaamcommissies je weer flink in het ootje: in Aalten ligt `Het Verzet` iets ten oosten van `Bevrijding`, `Spitfire` is een straat in Nootdorp en `Anne Frank` is een straat in Bunschoten-Spakenburg (om de hoek bij `Grebbelinie`).

###Vals positieven elimineren

Er is wel het één en ander te bedenken waar de kwaliteit van de data mee verbeterd kan worden. Zo zal alleen al het kritisch kijken naar gevonden straatnamen waar geen ‘straat’, ‘weg’, ‘plein’, etc. in voorkomt al een hoop problemen oplossen. Ook gebieden of landen die je niet zo snel binnen je dataset verwacht zou je aan een nader onderzoek kunnen onderwerpen. Zo geocodeerden we foutief naar pittoreske plaatsjes in de Verenigde Staten als ‘Uniform’, ‘February’, ‘August’, ‘Volt’, ‘Exile’, ‘Library’, ‘Channel’, ‘Lord’, ‘Brief’, ‘Social’, ‘Sector’ en ‘Axis’.

In het geval van oorlogsbronnen konden we ook kijken welke termen niet in ‘coverage’ gevonden waren, en wel - veel - in tekst. In de toptien waren alleen de - Engelstalige - termen ‘Berlin’ en ‘Germany’ goed gegeocodeerd. De overige termen waren, van veel naar meest voorkomend: ‘Rijk’ (plaats in België), ‘Ridderzaal’ (straat in Eindhoven), ‘augustus’ (straat in Wijk bij Duurstede), ‘Straat’ (plaats bij Roermond), ‘november’ (straat in Heerhugowaard), ‘Markt’ (plaats in Gelderland), ‘Zoom’ (plaats bij Nunspeet) en met 515 records afgetekend op de eerste plaats: ‘Engels’ (plaats in Noorwegen).


###Vals negatieven

Het zal opgevallen zijn dat een zeer groot aantal termen (13.584) geen resultaat heeft opgeleverd. Dit hoeft geen tekortkoming te zijn, want er zijn met Named Entity Recognition veel termen uit tekstvelden geplukt die niets met geografie van doen hebben. Dat `Roode Leger`, `Houthakkers`, `Afdeling Algemeen Secretariaat`, `Reconstructie Bijeenkomst`, `Werkgeversverklaring|Voorbeeld`, `Feldurteile`,  `Alarmbereitschaft` en `Ausland` niets opgeleverd hebben is terecht, al zou je van de laatste term kunnen zeggen dat die wel degelijk een geografie aanduidt.

Een steekproef van honderd random gekozen uit tekst afkomstige resultaatloze termen leverde het volgende op:

<table>
<tr>
<td>51</td><td>geen geografische term</td>
<td>Zie de voorbeelden 'Roode Leger' en verder hierboven</td>
</tr>
<tr>
<td>13</td><td>gebouw, kamp, etc.</td>
<td>Wel lokatie, niet in geocoders te vinden, vaak ook historische naam: 'Haagse Koninklijke Schouwburg', 'Benthienkazerne', 'Wang Po', 'R.K. U.L.O.', 'Philipsfabrieken'.</td>
</tr>
<tr>
<td>12</td><td>wel geografische term, spelling afwijkend</td>
<td>'Mildwolda' i.p.v. Midwolda, 'Stassfurt' ipv Staßfurt, 'Oesterreich' i.p.v. Österreich, samentrekkingen als 'Rotterdam-Hillegersberg' en 'Tel Aviv-Jaffa', regio-aanduidingen als 'Eastern-Indonesia'</td>
</tr>
<tr>
<td>10</td><td>historische spelling of naam</td>
<td>'Denenmarken', 'Rijksweg Amsterdam-Velsen', veel Indonesische namen als 'Tjipalat' (Cipalat), 'Poerbolinggo' (Purbalingga), 'Djokja' (Jogjakarta)</td>
</tr>
<tr>
<td>9</td><td>organisatie, soms geografisch te duiden</td>
<td>'Mobilisatiebureau', 'H.Landstichting', 'Djokjasche vliegclub', ook kranten als 'N.R.C. Handelsblad' en 'Leeuwarder Koerier'</td>
</tr>
<tr>
<td>6</td><td>bijvoegelijk gebruikte geografische term</td>
<td>'Loosdrechtse', 'Mexicaanse', 'Zaanse', 'Haagse'</td>
</tr>
</table>

Op basis van deze bescheiden steekproef zou je kunnen stellen dat 35 tot 45 procent van deze resultaatloze termen vals negatief is. 

Kijk je naar records in plaats van naar termen, dan is dat percentage lager - gesorteerd op aantal records zitten er tussen de 30 meestvoorkomende uit tekst geëxtraheerde resultaatloze termen maar zeven die met enige wil geografisch genoemd zouden kunnen worden (waarbij ‘Zondagmiddagcabaret’ in twee keer zoveel records blijkt voor te komen als ‘Europe’).

Zeker is dat er bij de extractie van termen uit tekst ook veel geografische aanduidingen over het hoofd zijn gezien. Dat zou het percentage weer hoger maken. Het blijft gissen, kortom, maar dat pakweg de helft niet wordt opgepikt of niet eenduidig wordt gegeocodeerd lijkt aannemelijk.


###Vals negatieven alsnog oplossen

Het zou goed zijn te kijken naar (of de Historische Geocoder zelf aan te vullen met) een lijst historische Indonesische namen. Voorts zouden een lijst kampen ('Wang Po'), andere oorlogsgerelateerde geografische namen (‘Hitler-Deutschland’) of een referentielijst verdwenen bouwwerken ('Bentheinkazerne') ook helpen.

Geautomatiseerd zou een enkele term nog wel op te lossen zijn ('ss/ß' en 'oe/ö', samentrekkingen uit elkaar halen), maar veel termen ontberen context ('Zaanse wethouder' of 'Zaanse mosterd', welk 'mobilisatiebureau'?).

Handmatig sla je snel een flinke slag door de meestvoorkomende louter uit tekst geëxtraheerde resutlaatloze termen even langs te lopen. Door alleen ‘den Niederlanden’ en ‘Deutschland’ op te lossen geocodeer je in één klap 1994 records.

De enige manier om een honderd procent score te naderen lijkt vooralsnog alleen haalbaar door een mens elk record afzonderlijk te laten beschrijven.


###Meerdere resultaten

Er waren 2108 termen die bij het geocoderen meerdere resultaten opleverden en dus niet eenduidig waren te benoemen. Soms zat de fout in de Historische Geocoder, waar het alignen blijkbaar niet altijd even effectief verlopen is. De GeoNames Schiphol wordt als een andere plaats gezien dan de TGN Schiphol. Voor Den Bosch geldt hetzelfde.

Vaak zijn er ook meerdere plaatsen met dezelfde naam - er zijn, nog los van Jakarta, Batavia's in Suriname, de Verenigde Staten en Argentinië. Er zijn Middelburgen in Zeeland en Zuid-Holland. Er is een Dam in Ameide, Arkel, Alblasserdam en Amsterdam (om ons even tot de plaatsen met een A te beperken). Het Binnenhof ligt in Den Haag, maar ook in meer dan vijftig andere plaatsen.


##Gegeocodeerd, en dan?

###Geografisch zoeken - mogelijkheden

Dat een kaart je zoekresultaten inzichtelijker maakt zal niemand ontkennen - je krijgt meteen een goed beeld van de geografische spreiding van hetgeen je naar zocht.
Geometrieën (puntjes, lijnen of polygonen) geven je de mogelijkheid te zoeken op nabijheid, binnen een bounding box (een rechthoek tussen twee schuin tegenover elkaar liggende coördinaten) of een polygoon (een veelhoek die je bijvoorbeeld de mogelijkheid geeft te zoeken binnen het gebied ‘De Veluwe’).

figuur 1: inzicht in aantallen records per woonplaats


Een goed gestandaardiseerde geografische aanduiding brengt behalve geometrie ook hiërarchie binnen handbereik. Van een GeoName of BAG id kan je eenvoudig achterhalen in welke plaats, gemeente, provincie of welk land het ligt. Sla je deze hiërarchische gegevens op, dan kan je dus goed zoeken op ‘verzetskranten gelderland’ - ook als die verzetskranten oorspronkelijk alleen met plaatsnamen getagd waren. Dit is ook te gebruiken voor exports.

Om dit alles mogelijk te maken moeten de eenduidige resultaten die het geocoderen heeft opgeleverd opgenomen worden in de Elastic Search index van Netwerk Oorlogsbronnen. Binnen deze pilot zullen NDJSON bestanden gemaakt worden waarmee Trifork dit eenvoudig kan doen. NDJSON staat voor ‘newline delimited json’, een formaat waarin elke regel een JSON array bevat, met in dit geval de NIOD identifier, geometrie en hiërarchie: plaats, gemeente, provincie, land.

Zijn de resultaten in de Elastic Search index opgenomen, dan is het aanpassen van de tekstuele zoekinterface relatief weinig werk. De nieuw geïndexeerde velden zullen binnen de bestaande API op Elastic Search toegankelijk komen. De opgeslagen hiërarchie komt vooral hierbij van pas. Een kaartinterface zal wat meer inspanning kosten, maar ook die is te overzien. Sowieso is het aan te bevelen een prototype van zo’n kaartinterface te maken, al was het maar om intern de waarde van het geocoderingsproces verder te kunnen evalueren. 

###Verrijkte data thuisbrengen

Het is een goed idee enige moeite te doen verrijkingen in het collectie beheer systeem van de data-eigenaar zelf te krijgen. Daar lopen de verrijkingen de minste kans verloren te gaan. Staan de verrijkingen ergens bij een aggregator op een server, dan is het met de gegevens gedaan zodra de aggregator ermee ophoudt. Of de gegevens worden na een aantal jaren door de aggregator zelf terzijde geschoven omdat de kwaliteit niet optimaal is. De kwaliteit zal in ieder geval niet verbeteren, want het is niet mogelijk de gegevens te editen. 

Ook niet denkbeeldig is dat de data-eigenaar zijn 'permalinks' of 'persistent' identifiers (als die er al waren) wijzigt en de koppelingen in de verrijkingen verwijzen naar een niet meer terug te vinden object. In de praktijk gaan persistent identifiers vaak niet langer mee dan de periode waarin een data-eigenaar een bepaald collectiebeheersysteem gebruikt.

Voor erfgoedinstellingen is het van groot belang is de verrijkte data in de eigen collectiebeheersystemen wordt opgenomen. Met deze verrijkingen kan ook binnen eigen systemen de “waar-”vraag beter beantwoord worden, hetgeen zowel geografisch zoeken als een geografische presentatie op de kaart dichterbij brengt. Het opnemen in de eigen systemen vereist alleen een juiste koppeling op uniek identificatienummer en mogelijk (extra) velden waarin de geografische verrijkingen terecht kunnen komen. Als de erfgoedinstelling redactie voert zullen de verrijkingen nog beter worden en zal de “waar”-vraag in de toekomst, zowel binnen de eigen systemen als op andere aggregerende platformen (Europeana, DimCon), nog beter beantwoord kunnen worden. In het eigen collectiebeheersysteem schrijft en schaaft de data-eigenaar immers al regelmatig aan zijn collectiedata. 

Een argument dat soms tegen teruglevering gebruikt wordt is dat het gebruikte collectiebeheersysteem er 'niet klaar voor is'. Inderdaad zou het fijn zijn als die softwarepakketten online koppelingen met veelgebruikte thesauri zouden faciliteren, maar welbeschouwd is er meestal wel een weg als er een wil is. Voor het opslaan van een URI is niet meer dan een tekstveld nodig.

Voor het NIOD betekent dit dat de geocoderingen op haar collecties (NIOD archieven en collecties, Beeldbank WO2 en NIOD bibliotheek) in haar bestaande collectiebeheersystemen zullen moeten worden opgenomen. Bekenen moet worden of hier aanpassingen aan de systemen door de verschillende leveranciers voor nodig zijn.

De verrijkingen zouden voor de leverende instellingen klaargezet moeten worden, zodat die de verrijkingen in kunnen lezen in het eigen systeem. Een korte rondvraag langs enkele leverende instellingen zou de vraag moeten beantwoorden hoe dat het handigst te doen is. Via oai-pmh is een optie, maar misschien hebben instellingen liever een csv-bestand. Onderzoek moet uitwijzen of de verrijkingen in alle gevallen überhaupt nog te koppelen zijn (komen de identifiers in Oorlogsbronnen nog overeen met die in de collecties zelf?).

###Geocoderen tijdens het aggregeren

Bekijken hoe records tijdens het aggregeren gegeocodeerd kunnen worden was deel van de opdracht van deze pilot. Het moge duidelijk zijn dat dit een lastig proces is met veel kans op zowel vals positieven als vals negatieven. In de pilot zijn in de scripts, bijvoorbeeld om hiërarchie af te leiden uit het ‘coverage’ veld, regelmatig op specifieke datasets toegespitste stukjes code geschreven. Tijdens het proces kan het nodig zijn om op basis van de resultaten de code enigszins aan te passen. Geocoderen geeft, kortom, betere resultaten als het geen louter automatisch proces is. 

Praktisch is het aanroepen van API’s een duur proces - het aggregeren duurt zo een factor tien langer dan zonder. En de GeoNames limiet van tweeduizend aanroepen per uur voor gratis gebruik speelt ook mee.

Wil je ondanks dat het geocoderen toch automatisch tijdens het aggregeren laten geschieden, dan is het logisch je te beperken tot termen uit ‘coverage’ (termen uit tekst geven, zonder enige controle, teveel vals positieven). Je zou je verder kunnen beperken (en de kans op vals positieven kunnen verkleinen) door termen niet tegen een api aan te houden, maar tegen een lijst van eerder gegeocodeerde (en enigszins gecontroleerde) termen.

Als deze pilot één ding heeft duidelijk gemaakt, dan is het dat gebruik van URI’s of id’s van geografische thesauri als GeoNames, TGN of BAG veel problemen oplossen - de eenduidigheid die ze met zich meebrengen reduceert de kans op vals positieven in ieder geval tot vrijwel nul. Een script dat bij het aantreffen van zo’n URI gegevens als geometrie en hiërarchie ophaalt is dan ook redelijk eenvoudig te schrijven. 

De aanbeveling is dan ook om zo’n script in het aggregatieproces op te nemen. Op tijd die nodig is om API’s aan te roepen kan bespaard worden door intern een database aan te leggen van URI’s met benodigde informatie - dan hoef je niet steeds opnieuw geometrie en hiërarchie van ‘Amsterdam’ op te halen. Het faciliteren en ‘belonen’ van instellingen die URI’s gebruiken zal uiteindelijk juist de aggregator ten goede komen, omdat aangeleverde data uiteindelijk eenduidiger zal zijn.

De koninklijke weg om data van geografische verrijkingen te voorzien is: gecombineerd scriptmatig / handmatig geocoderen als in deze pilot, de resultaten opnemen in het collectiebeheersysteem van leverende instelling, bij export van zo’n collectie URI’s opnemen in ‘coverage’, bij inlezen van collectie door Oorlogsbronnen op basis van URI naam, hiërarchie en geometrie ophalen.


##Conclusies en aanbevelingen

GeoNames is de handigste thesaurus gebleken om plaatsen, provincies, landen (en typen als water, eiland, museum, etc) mee te benoemen.

Geografische thesauri verbeteren helpt jezelf en anderen. We hebben een aantal historische namen (‘Nederlands-Indië’, ‘Sovjet-Unie’, ‘Joegoslavië’, ‘Oranjehotel’) en een aantal kampen (‘Kampong Makassar’, ‘Lampersari’, ‘Kamp Westerbork’) aan GeoNames toegevoegd.

Het NIOD zou kunnen overwegen de intern gebruikte lijst met kampen, etc. te publiceren, liefst als linked data. Daarbij kunnen o.a. links naar bestaande of aan te maken GeoNames items opgenomen worden. Dit vanuit het idee dat het NIOD niet alleen de aangewezen partij is om oorlogsgerelateerde collectiemetadata centraal te ontsluiten, maar dat datzelfde geldt voor oorlogsgerelateerde terminologie.

De BAG is de beste (en eigenlijk ook de enige) thesaurus gebleken om (huidige Nederlandse) adressen en gebouwen te benoemen.

Termen uit Coverage leveren vrijwel geen false positives op, maar een kwart tot een derde van de termen is niet in één keer eenduidig te geocoderen.

Met NER verkregen termen uit tekstvelden komen we op 10-20% false positives. Met semi-automatische processen is dat percentage tot onder de 10% te brengen.

In de hele keten (aggregatie, collectiebeheersysteem, data-ontsluiting) zou gebruik van URI’s mogelijk gemaakt moeten worden.

Verrijkingen die niet in het collectiebeheersysteem, maar alleen bij een aggregator leven zijn beperkt houdbaar.

De verrijkingen moeten aan de leverende instellingen worden aangeboden.

Het NIOD zou de verrijkingen in ieder geval in haar eigen collectiebeheersystemen (Bibliotheek, Archief en Beeldbank) op moeten nemen.
