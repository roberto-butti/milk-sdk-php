<?php

/**
 * /spaces/{spaceId}/iterate
 * Iterates all of the features in the space. The features in the response are ordered so that no feature is returned twice.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/iterateFeatures
 *
 */
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpaceFeature;
use \Rbit\Milk\Xyz\XyzConfig;
use \Rbit\Milk\Utils\Obj;

function print_row($item, $key)
{
    Obj::echo($item);
    echo "------------------------" . PHP_EOL;
}


$spaceId = readline("Space ID : ");
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = new XyzSpaceFeature(XyzConfig::getInstance());
$result = $xyzSpaceFeature->iterate($spaceId)->get();
array_walk($result->features, 'print_row');
echo PHP_EOL;
