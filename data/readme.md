#Pilot Geocoderen Oorlogsbronnen - data

##results

In [falsepositives.geojson](falsepositives.geojson) you will find some results we rejected.

##woonplaatsen

In the [MySQL table woonplaatsen](woonplaatsen.sql) we aligned 2493 BAG places with their GeoNames counterparts. Records include hierarchy (municipality, province), simplified geojson BAG borders for use in web applications, and of course BAG id's and GeoNames coordinates.

We used this table to quickly find hierarchy without making apicalls to GeoNames and to create a map that visualises the results.

![map](../images/provkaart.jpg)