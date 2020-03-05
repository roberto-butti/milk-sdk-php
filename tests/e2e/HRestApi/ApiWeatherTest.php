<?php

use PHPUnit\Framework\TestCase;
use \Rbit\Milk\HRestApi\Weather\ApiWeather;

use \Rbit\Milk\HRestApi\Common\ApiConfig;

class ApiWeatherTest extends TestCase
{
    protected static $apiToken;
    protected static $weather;

    public static function setUpBeforeClass(): void
    {
        Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../")->load();
        self::$apiToken = getenv('HERE_API_KEY');
        self::$weather = ApiWeather::instance(self::$apiToken);

    }

    public static function tearDownAfterClass(): void
    {

    }

    protected  function setUp(): void
    {
        self::$weather->reset();
    }

    protected  function tearDown(): void
    {
    }





    public function testGetWeatherBerlin()
    {

        $responseJson = self::$weather
            ->product("forecast_7days")
            ->name("Roncade")
            ->cacheResponse(true)
            ->language("it")
            ->getJson();

        $this->assertJson($responseJson, "Che Response JSON Weather");
        //self::$weather->debug();

        //echo $responseJson;

    }

    public function testGetWeatherLatitudeLongitude()
    {

        $responseJson = self::$weather
            ->product("forecast_7days")
            ->latlon(41.89193, 12.51133)
            ->cacheResponse(true)
            ->language("it")
            ->getJson();

        $this->assertJson($responseJson, "Check Response JSON Weather with latlong");
        //self::$weather->debug();

        //echo $responseJson;

    }





}
