<?php
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Utils\Obj;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = getenv('XYZ_ACCESS_TOKEN');
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::instance($xyzToken);
$o1 = $xyzSpace->delete($spaceId);
echo $o1->getBody();

