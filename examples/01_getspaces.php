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

echo "GET" . PHP_EOL;
$s = XyzSpace::instance($xyzToken)->get();
array_walk($s, 'print_row');


echo "GET OWNER ALL" . PHP_EOL;
echo "---". $xyzToken;
$spaces = XyzSpace::instance($xyzToken);
$spaces->setToken($xyzToken);
$s =  $spaces->ownerAll()->get();
$spaces->debug();
array_walk($s, 'print_row');
$spaces->debug();

echo "GET OTHERS" . PHP_EOL;
$s =  XyzSpace::instance($xyzToken)->ownerOthers()->get();
array_walk($s, 'print_row');

echo "GET INCLUDE RIGHTS" . PHP_EOL;
$s =  XyzSpace::instance($xyzToken)->ownerOthers()->includeRights()->get();
array_walk($s, 'print_row');


