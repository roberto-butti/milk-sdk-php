<?php

/**
 * /spaces/{spaceId}
 * Returns the space definition
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Spaces/getSpace
 */
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Utils\Obj;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = getenv('XYZ_ACCESS_TOKEN');
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::instance($xyzToken);
$xyzSpace->reset();
$o1 = $xyzSpace->spaceId($spaceId)->get();

Obj::echo($o1);
