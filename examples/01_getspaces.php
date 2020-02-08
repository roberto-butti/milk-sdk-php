<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Xyz\Common\XyzConfig;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

function print_row($item, $key)
{
    echo $key+1 . " ". $item->id . " ". $item->owner . " " . $item->title."\n";
}

$xyzToken = getenv('XYZ_ACCESS_TOKEN');
$space = XyzSpace::instance($xyzToken);


echo "GET" . PHP_EOL;
$s = $space->get();
array_walk($s, 'print_row');


echo "GET OWNER ALL" . PHP_EOL;
$space->reset();
$s =  $space->ownerAll()->getLimited(2);
$space->debug();
array_walk($s, 'print_row');


echo "GET OTHERS" . PHP_EOL;
$space->reset();
$s =  $space->ownerOthers()->getLimited(2);
array_walk($s, 'print_row');

echo "GET INCLUDE RIGHTS" . PHP_EOL;
$space->reset();
$s =  $space->ownerOthers()->includeRights()->getLimited(2);
array_walk($s, 'print_row');
$space->debug();
