<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Xyz\Space\XyzSpaceStatistics;
use \Rbit\Milk\Xyz\Common\XyzConfig;

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
    }

    public function testGetSpaceNotFound() {
        $o = XyzSpace::instance()->spaceId("aa")->get();
        $this->assertEquals("ErrorResponse", $o->type, "Testing Space not found, Error response");


        $o = XyzSpace::instance()->spaceId("aa")->getResponse();
        $this->assertEquals(404, $o->getStatusCode(), "Testing Space not found, 404 http status");
    }



    public function testGetStatistics()
    {
        $spaceIdTest = "bB6WZ2Sb";
        $o1 = XyzSpaceStatistics::instance()->spaceId($spaceIdTest)->get();
        $this->assertEquals("StatisticsResponse", $o1->type, "Testing List Feature Statistics");
    }

    public function testCreateDeleteSpace()
    {
        $space = XyzSpace::instance();
        $response = $space->create("My Space", "Description");
        //$space->debug();
        $jsonResponse =  json_decode($response->getBody());
        //var_dump($jsonResponse);
        $spaceId = $jsonResponse->id;
        $this->assertIsString($spaceId, "Check spaceid after create");
        //echo $spaceId;
        //$o1 = XyzSpaceStatistics::instance()->spaceId($spaceId)->get();
        //var_dump($o1);
        $response = XyzSpace::instance()->delete($spaceId);

    }

    public function testGetSpaceWrongToken() {
        $xyzSpace = XyzSpace::instance();
        $xyzSpace->setToken("asasasasasa");
        $o = $xyzSpace->spaceId("aa")->getResponse();
        $this->assertEquals(401, $o->getStatusCode(), "Testing Space no permission wrong token, 401 http status");
        $xyzSpace->setToken(getenv('XYZ_ACCESS_TOKEN'));

    }

}
