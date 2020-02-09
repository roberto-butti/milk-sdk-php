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
    Obj::echo($item->properties);
    echo "------------------------" . PHP_EOL;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

$spaceId = "sSQRaPFS";

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$xyzSpaceFeature->cleanSearchParams();
$xyzSpaceFeature->addSearchParams("p.cad", 76);
$result = $xyzSpaceFeature->search($spaceId)->get();

//Obj::echo($result);
//echo "------------------------" . PHP_EOL;
array_walk($result->features, 'print_row');

$xyzSpaceFeature->debug();
echo PHP_EOL;
