<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;

function print_row($item, $key)
{
    echo $key+1 . " ". $item->id . " ". $item->owner . " " . $item->title."\n";
}

$s = XyzSpace::instance()->get();
array_walk($s, 'print_row');

$s =  XyzSpace::instance()->ownerAll()->get();
array_walk($s, 'print_row');


$s = XyzSpace::instance()->get();
array_walk($s, 'print_row');
