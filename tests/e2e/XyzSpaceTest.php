<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\Xyz\Space\XyzSpace;
use \Rbit\Milk\Xyz\Space\XyzSpaceStatistics;
use \Rbit\Milk\Xyz\Common\XyzConfig;

class XyzSpaceTest extends TestCase
{
    protected static $xyzToken;
    protected static $space;
    protected static $spaceStatistics;
    protected static $spaceId;

    public static function setUpBeforeClass(): void
    {
        Dotenv\Dotenv::createImmutable(__DIR__ . "/../../")->load();
        self::$xyzToken = getenv('XYZ_ACCESS_TOKEN');
        self::$space = XyzSpace::instance(self::$xyzToken);
        self::$spaceStatistics = XyzSpaceStatistics::instance(self::$xyzToken);

        $spaceTitle = "My Space";
        $spaceDescription = "Description";
        $response = self::$space->create($spaceTitle, $spaceDescription);

        $jsonResponse =  json_decode($response->getBody());
        self::$spaceId = $jsonResponse->id;
    }

    public static function tearDownAfterClass(): void
    {
        $response = self::$space->delete(self::$spaceId);
    }

    protected  function setUp(): void
    {
        self::$space->reset();
    }

    protected  function tearDown(): void
    {

    }





    public function testGetListSpacesByConfig()
    {

        $conf = XyzConfig::getInstance(self::$xyzToken);
        $this->assertIsArray(XyzSpace::config($conf)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::config($conf)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::config($conf)->ownerAll()->get(), "Testing List spaces with Owner all");
    }


    public function testGetListSpacesByInstance()
    {

        $this->assertIsArray(XyzSpace::instance(self::$xyzToken)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::instance(self::$xyzToken)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::instance(self::$xyzToken)->ownerAll()->get(), "Testing List spaces with Owner all");
    }



    public function testGetListSpacesByToken()
    {
        $this->assertIsArray(XyzSpace::setToken(self::$xyzToken)->get(), "Testing List spaces as array");
        $this->assertIsArray(XyzSpace::setToken(self::$xyzToken)->includeRights()->get(), "Testing List spaces with Rights as array");
        $this->assertIsArray(XyzSpace::setToken(self::$xyzToken)->ownerAll()->get(), "Testing List spaces with Owner all");
    }

    public function testGetOneSpace()
    {
        $space = self::$space->spaceId(self::$spaceId)->get();
        $this->assertEquals(self::$spaceId, $space->id, "Testing List 1 space, with space id: " . self::$spaceId);
    }

    public function testGetSpaceNotFound()
    {

        $o = self::$space->spaceId("aa")->get();
        $this->assertEquals("ErrorResponse", $o->type, "Testing Space not found, Error response");


        $o = self::$space->spaceId("aa")->getResponse();
        $this->assertEquals(404, $o->getStatusCode(), "Testing Space not found, 404 http status");
    }




    public function testGetStatistics()
    {

        $o1 = self::$spaceStatistics->spaceId(self::$spaceId)->get();
        $this->assertEquals("StatisticsResponse", $o1->type, "Testing List Feature Statistics");
    }



    public function testCreateDeleteSpace()
    {

        $spaceTitle = "My Space";
        $spaceDescription = "Description";
        $response = self::$space->create($spaceTitle, $spaceDescription);
        $jsonResponse =  json_decode($response->getBody());
        $spaceId = $jsonResponse->id;
        $this->assertIsString($spaceId, "Check spaceid after create");
        self::$space->reset();
        $response = self::$space->spaceId($spaceId)->get();
        $this->assertEquals($spaceTitle, $response->title, "Get after Create Space, check title");
        $this->assertEquals($spaceDescription, $response->description, "Get after Create Space, check description");

        self::$space->reset();
        $obj = new \stdClass;
        $obj->title = "Edited Title";
        $obj->description = "Edited Description";
        $retVal = self::$space->update($spaceId, $obj);
        $this->assertEquals($spaceId, json_decode($retVal->getBody())->id, "Update Space, check spaceId");
        $this->assertEquals($obj->title, json_decode($retVal->getBody())->title, "Update Space, check title");
        $this->assertEquals($obj->description, json_decode($retVal->getBody())->description, "Update Space, check description");

        self::$space->reset();
        $response = self::$space->spaceId($spaceId)->cacheResponse(false)->get();
        $this->assertEquals($spaceId, $response->id, "Get after Update Space, No cache, check spaceId");
        $this->assertEquals($obj->title, $response->title, "Get after Update Space, No cache, check title");
        $this->assertEquals($obj->description, $response->description, "Get after Update Space, No cache, check description");

        self::$space->reset();
        $response = self::$space->delete($spaceId);
        $this->assertEquals(200, $response->getStatusCode(), "Delete Sapce, check status code");
    }



    public function testGetSpaceWrongToken()
    {

        self::$space->setToken("asasasasasa");
        $o = self::$space->spaceId("aa")->getResponse();
        $this->assertEquals(401, $o->getStatusCode(), "Testing Space no permission wrong token, 401 http status");
        self::$space->setToken(self::$xyzToken);
    }



}
