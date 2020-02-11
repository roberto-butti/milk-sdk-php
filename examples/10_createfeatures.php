<?php
require __DIR__ . "/../vendor/autoload.php";

use Rbit\Milk\Utils\GeoJson as UtilsGeoJson;
use \Rbit\Milk\Xyz\Space\XyzSpaceFeatureEditor;
use \Rbit\Milk\Utils\GeoJson;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
$feature = XyzSpaceFeatureEditor::instance($xyzToken);


$geoJson = new GeoJson();
$properties = [
    "name" => "Colosseo",
];
$geoJson->addPoint(41.890251, 12.492373, $properties ,1);
$result = $feature->addTags([ "milk"])->create("eFM936rJ", $geoJson->getString());

$feature->debug();
//$result =  json_decode($result->getBody());
var_dump($result);
