<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\XyzSpaceFeature;
use \Rbit\Milk\Xyz\XyzConfig;

class XyzSpaceFeatureTest extends TestCase
{
    public function testGetListSpaces()
    {
        $xyzSpaceFeature = new XyzSpaceFeature(XyzConfig::getInstance());
        $o = $xyzSpaceFeature->spaceId("bB6WZ2Sb")->iterate()->getResponse();
        $this->assertEquals(200, $o->getStatusCode(), "Testing List Feature");

    }
}
