<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\HRestApi\Common\ApiCredentials;
use Rbit\Milk\HRestApi\Common\ApiConfig;

class ApiConfigTest extends TestCase
{
    protected static $apiToken = "";

    public static function setUpBeforeClass(): void
    {
        Dotenv\Dotenv::createImmutable(__DIR__ . "/../../")->load();
        self::$apiToken = getenv('HAPI_ACCESS_TOKEN');
    }



    public function testLoadConfig()
    {
        $config = ApiConfig::getInstance(self::$apiToken, "https://weather.ls.hereapi.com", "WEATHER_PROD");

        $this->assertIsString($config->getHostname(), "Checking Hostname");
        $this->assertIsString($config->getCredentials()->getAccessToken(), "Checking Access Token");
    }

    public function testCredential()
    {


        $credential = new ApiCredentials(self::$apiToken);
        $this->assertEquals(self::$apiToken, $credential->getAccessToken(), "Checking Credential Access token");
    }
}
