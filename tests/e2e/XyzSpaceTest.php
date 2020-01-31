<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\XyzSpace;
use \Rbit\Milk\Xyz\XyzConfig;

class XyzSpaceTest extends TestCase
{
    public function testGetListSpacesByConfig()
    {
        $conf = XyzConfig::getInstance();
        $this->assertIsArray(XyzSpace::config($conf)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::config($conf)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::config($conf)->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetListSpacesByInstance()
    {
        $this->assertIsArray(XyzSpace::instance()->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::instance()->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::instance()->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetListSpacesByToken()
    {
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetOneSpace()
    {
        $conf = XyzConfig::getInstance();
        $spaceIdTest = "bB6WZ2Sb";
        $space = XyzSpace::config($conf)->spaceId($spaceIdTest)->get();
        $this->assertEquals($spaceIdTest, $space->id, "Testing List 1 space, with space id: ". $spaceIdTest);

        /*
        $conf = XyzConfig::getInstance();
        $xyzSpace = new XyzSpace();

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
        */
    }
}
