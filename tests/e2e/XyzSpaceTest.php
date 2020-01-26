<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;

class XyzSpaceTest extends TestCase
{
    public function testGetListSpaces()
    {
        $xyzSpace = new XyzSpace(XyzConfig::getInstance());
        $this->assertIsArray($xyzSpace->get(), "Testing List spaces as array");

        $xyzSpace = new XyzSpace(XyzConfig::getInstance());
        //$xyzSpace->reset();
        $this->assertIsArray($xyzSpace->includeRights()->get(), "Testing List spaces with Rights as array");

        $xyzSpace->reset();
        $this->assertIsArray($xyzSpace->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetOneSpace()
    {
        $xyzSpace = new XyzSpace(XyzConfig::getInstance());

        $o = $xyzSpace->includeRights()->spaceId("bB6WZ2Sb")->get();
        $this->assertEquals("bB6WZ2Sb", $o->id, "Testing List 1 space");

        $xyzSpace->reset();
        $o1 = $xyzSpace->spaceId("bB6WZ2Sb")->statistics()->get();
        $this->assertEquals("StatisticsResponse", $o1->type, "Testing List Feature Statistics");

        $o = $xyzSpace->spaceId("aa")->get();
        $this->assertEquals("ErrorResponse", $o->type, "Testing Space not found, Error response");


        $o = $xyzSpace->spaceId("aa")->getResponse();
        $this->assertEquals(404, $o->getStatusCode(), "Testing Space not found, 404 http status");


        $xyzSpace->setToken("asasasasasa");
        $xyzSpace->reset();
        $o = $xyzSpace->spaceId("aa")->getResponse();
        $this->assertEquals(401, $o->getStatusCode(), "Testing Space no permission wrong token, 401 http status");
    }
}
