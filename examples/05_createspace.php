<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
$space = XyzSpace::instance($xyzToken);
$result = $space->create("My Space", "DEscripton");

$space->debug();
$result =  json_decode($result->getBody());
echo "SPACE CREATED: " . $result->id;
