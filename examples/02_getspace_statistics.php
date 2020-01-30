<?php
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;

function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}

$spaceId = readline("Space ID : ");
/** XyzConfig $config */
$config = XyzConfig::getInstance();
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::config($config);
$o1 = $xyzSpace->spaceId($spaceId)->statistics()->get();


echo "The Spaces has {$o1->count->value} features " . PHP_EOL;
echo $o1->count->estimated ? "The count is estimated" : "The count is real:)";
echo PHP_EOL;

echo "The Spaces is {$o1->byteSize->value} byte " . PHP_EOL;
echo $o1->byteSize->estimated ? "The size is estimated" : "The size is real:)";
echo PHP_EOL;
