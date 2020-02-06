<?php
// include the autoload.php file
require "./vendor/autoload.php";
// declare all imports via "use"
use Rbit\Milk\Xyz\Space\XyzSpace;
// load environment configuration (via Dotenv)
Dotenv\Dotenv::createImmutable(__DIR__)->load();
// get your Token
$xyzToken = getenv("XYZ_ACCESS_TOKEN");
// Ggt your XYZ Spaces
$s = XyzSpace::setToken($xyzToken)->get();
// display your result
var_dump($s);
