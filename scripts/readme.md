#Scripts used in Pilot Geocoderen Oorlogsbronnen

Scripts are all PHP, with the exception of the Python Postagger (find documentation [here](http://www.clips.ua.ac.be/pages/pattern-nl)).

Please note that this is not an application ready to run, but a set of scripts that can be used for different tasks. Scripts have been adjusted during the process to specific datasets or tasks, so if you use them you should probably do the same (and change db and geonames username / password).

###steps in chronological order

- create database with [nob.sql](nob.sql)
- import metadata records [through oai-pmh](http://www.oorlogsbronnen.nl/datablog-oai-pmh) (scripts not included here)
- extract terms from `coverage` using [terms-from-coverage.php](terms-from-coverage.php)
- extract terms from `title` and `description` using [terms-from-txt.php](terms-from-txt.php)
- extract terms from `subject` using [terms-from-subject.php](terms-from-subject.php)
- geocode with HGC using [geocode-with-hgc.php](geocode-with-hgc.php)
- geocode with GeoNames using [geocode-with-geonames.php](geocode-with-geonames.php)
- add hierarchy to terms with [add-hierarchy.php](add-hierarchy.php)