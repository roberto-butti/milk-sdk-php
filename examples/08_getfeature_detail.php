<?php

/**
 * /spaces/{spaceId}/features/{featureId}
 * Retrieves the feature with the provided identifier.
 * https://xyz.api.here.com/hub/static/swagger/#/Read%20Features/getFeature
 *
 */
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpaceFeature;
use \Rbit\Milk\Utils\Obj;

function print_row($item, $key)
{
    Obj::echo($item);
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = "zwtPDoOU";
$featureId = "5a07d0ed16707af6bf09ac23e591ddc5";

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->feature($featureId, $spaceId)->get();

Obj::echo($result);
echo "------------------------" . PHP_EOL;
//array_walk($result->features, 'print_row');
echo PHP_EOL;
