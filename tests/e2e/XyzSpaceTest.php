<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Xyz\Space\XyzSpaceStatistics;
use \Rbit\Milk\Xyz\Common\XyzConfig;

class XyzSpaceTest extends TestCase
{
    public function testGetListSpacesByConfig()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $conf = XyzConfig::getInstance($xyzToken);
        $this->assertIsArray(XyzSpace::config($conf)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::config($conf)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::config($conf)->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetListSpacesByInstance()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $this->assertIsArray(XyzSpace::instance($xyzToken)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::instance($xyzToken)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::instance($xyzToken)->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetListSpacesByToken()
    {
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::setToken(getenv('XYZ_ACCESS_TOKEN'))->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetOneSpace()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $conf = XyzConfig::getInstance($xyzToken);
        $spaceIdTest = "bB6WZ2Sb";
        $space = XyzSpace::config($conf)->spaceId($spaceIdTest)->get();
        $this->assertEquals($spaceIdTest, $space->id, "Testing List 1 space, with space id: ". $spaceIdTest);
    }

    public function testGetSpaceNotFound() {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $o = XyzSpace::instance($xyzToken)->spaceId("aa")->get();
        $this->assertEquals("ErrorResponse", $o->type, "Testing Space not found, Error response");


        $o = XyzSpace::instance($xyzToken)->spaceId("aa")->getResponse();
        $this->assertEquals(404, $o->getStatusCode(), "Testing Space not found, 404 http status");
    }



    public function testGetStatistics()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $spaceIdTest = "bB6WZ2Sb";
        $o1 = XyzSpaceStatistics::instance($xyzToken)->spaceId($spaceIdTest)->get();
        $this->assertEquals("StatisticsResponse", $o1->type, "Testing List Feature Statistics");
    }

    public function testCreateDeleteSpace()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $space = XyzSpace::instance($xyzToken);
        $response = $space->create("My Space", "Description");

        $jsonResponse =  json_decode($response->getBody());
        $spaceId = $jsonResponse->id;

        $this->assertIsString($spaceId, "Check spaceid after create");

        $obj = new \stdClass;
        $obj->title = "Edited Title";
        $obj->description = "Edited Description";
        $retVal = $space->update($spaceId, $obj);

        $this->assertEquals($spaceId,  json_decode($retVal->getBody())->id, "Update Space, check spaceId");
        $this->assertEquals($obj->title,  json_decode($retVal->getBody())->title, "Update Space, check title");
        $this->assertEquals($obj->description,  json_decode($retVal->getBody())->description, "Update Space, check description");

        $response = $space->spaceId($spaceId)->get();
        $this->assertEquals($spaceId,  $response->id, "Get after Update Space, check spaceId");
        $this->assertEquals($obj->title,  $response->title, "Get after Update Space, check title");
        $this->assertEquals($obj->description,  $response->description, "Get after Update Space, check description");


        $response = XyzSpace::instance($xyzToken)->delete($spaceId);
        $this->assertEquals( 200, $response->getStatusCode(), "Delete Sapce, check status code" );

    }

    public function testGetSpaceWrongToken() {

        $xyzSpace = XyzSpace::instance();
        $xyzSpace->setToken("asasasasasa");
        $o = $xyzSpace->spaceId("aa")->getResponse();
        $this->assertEquals(401, $o->getStatusCode(), "Testing Space no permission wrong token, 401 http status");
        $xyzSpace->setToken(getenv('XYZ_ACCESS_TOKEN'));

    }

}
