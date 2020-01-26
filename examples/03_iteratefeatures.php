<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpaceFeature;
use \Rbit\Milk\Xyz\XyzConfig;

function print_row($item, $key)
{
    foreach ($item->properties  as $key => $value) {
        if (gettype($value) == "string") {
            echo "$key => $value" . PHP_EOL;
        }
    }
    echo "------------------------" . PHP_EOL;
}


$spaceId = readline("Space ID : ");
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = new XyzSpaceFeature(XyzConfig::getInstance());
$result = $xyzSpaceFeature->iterate($spaceId)->get();
array_walk($result->features, 'print_row');
echo PHP_EOL;

