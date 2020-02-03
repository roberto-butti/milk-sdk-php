<?php
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Utils\Obj;



$spaceId = readline("Space ID : ");
/** XyzSpace $xyzSpace */
$xyzSpace = XyzSpace::instance();
$o1 = $xyzSpace->delete($spaceId);
echo $o1->getBody();

