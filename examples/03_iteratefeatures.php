<?php

/**
 * /spaces/{spaceId}/iterate
 * Iterates all of the features in the space. The features in the response are ordered so that no feature is returned twice.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/iterateFeatures
 *
 */
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpaceFeature;
use \Rbit\Milk\Xyz\Common\XyzConfig;
use \Rbit\Milk\Utils\Obj;

function print_row($item, $key)
{
    Obj::echo($item);
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = readline("Space ID : ");
$xyzToken = getenv('XYZ_ACCESS_TOKEN');
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->iterate($spaceId)->get();
array_walk($result->features, 'print_row');
echo PHP_EOL;
