<?php

/**
 * /spaces/{spaceId}
 * Returns the space definition
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Spaces/getSpace
 */
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;
use \Rbit\Milk\Utils\Obj;



$spaceId = readline("Space ID : ");
/** XyzSpace $xyzSpace */
$xyzSpace = new XyzSpace(XyzConfig::getInstance());

$xyzSpace->reset();
$o1 = $xyzSpace->spaceId($spaceId)->get();

Obj::echo($o1);
