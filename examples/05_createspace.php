<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;

$space = XyzSpace::instance();
$result = $space->create("My Space", "DEscripton");

$space->debug();
$result =  json_decode($result->getBody());
echo "SPACE CREATED: " . $result->id;
