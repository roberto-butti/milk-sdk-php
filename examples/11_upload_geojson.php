<?php
require __DIR__ . "/../vendor/autoload.php";


use \Rbit\Milk\Xyz\Space\XyzSpaceFeatureEditor;
use \Rbit\Milk\Xyz\Space\XyzSpace;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$space = XyzSpace::instance($xyzToken);

$spaceTitle = "Space for upload geojson";
$spaceDescription = "Space for upload geojson";
$response = $space->create($spaceTitle, $spaceDescription);
$jsonResponse =  json_decode($response->getBody());
$spaceId = $jsonResponse->id;

//$file = __DIR__. "/../tests/fixtures/subway_stations.geojson";

$file = "https://data.cityofnewyork.us/api/geospatial/arq3-7z49?method=export&format=GeoJSON";
$result = $feature
            ->addTags(["geojson"])
            ->geojson($file)
            ->create($spaceId);

$feature->debug();
