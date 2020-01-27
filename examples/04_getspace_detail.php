<?php
require __DIR__."/../vendor/autoload.php";

use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;



$spaceId = readline("Space ID : ");
/** XyzSpace $xyzSpace */
$xyzSpace = new XyzSpace(XyzConfig::getInstance());

$xyzSpace->reset();
$o1 = $xyzSpace->spaceId($spaceId)->get();

var_dump($o1);

