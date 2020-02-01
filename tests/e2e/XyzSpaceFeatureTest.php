<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\Xyz\Space\XyzSpaceFeature;

class XyzSpaceFeatureTest extends TestCase
{
    public function testGetListSpaces()
    {
        $xyzSpaceFeature = XyzSpaceFeature::instance()->iterate("bB6WZ2Sb")->getResponse();

        $this->assertEquals(200, $xyzSpaceFeature->getStatusCode(), "Testing List Feature");
    }
}
