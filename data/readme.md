#Pilot Geocoderen Oorlogsbronnen - data

##results

The ndjson files contain the geographical enrichments for each collection in Oorlogsbronnen: 

```
ARCHIEVEN, ARCHIEVENWO2, BBNA, BBWO2, DANS, DIGCOL, DIMCON, GETUIGENVERHALEN, GVNEVDO01, GVNEVDO02, GVNEVDO03, GVNMUSE01, GVNNIOD02, IIO_LEGER, IIO_ONAFHANKELIJK, IPNV, KITLV, MFORCE-MEDIA, NATIONAAL-ARCHIEF, OCLC, OIB, OORLOGSGRAVEN, OORLOGSMONUMENTEN, RAL
```
##false positives

In [falsepositives.geojson](falsepositives.geojson) you will find some results we rejected. Funny!

##woonplaatsen

In the [MySQL table woonplaatsen](woonplaatsen.sql) we aligned 2493 BAG places with their GeoNames counterparts. Records include hierarchy (municipality, province), simplified geojson BAG borders for use in web applications, and of course BAG id's and GeoNames coordinates.

We used this table to quickly find hierarchy without making apicalls to GeoNames and to create a map that visualises the results.

![map](../images/provkaart.jpg)