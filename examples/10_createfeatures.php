<?php
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpaceFeatureEditor;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
$feature = XyzSpaceFeatureEditor::instance($xyzToken);


$point = new \GeoJson\Geometry\Point([41.890251, 12.492373]);
$properties = [
    "name" => "Colosseo",
];
$f = new \GeoJson\Feature\Feature($point, $properties, 1);

$fs = new \GeoJson\Feature\FeatureCollection([$f]);
//echo  json_encode($fs);
$result = $feature->create("eFM936rJ", json_encode($fs));

$feature->debug();
//$result =  json_decode($result->getBody());
var_dump($result);
