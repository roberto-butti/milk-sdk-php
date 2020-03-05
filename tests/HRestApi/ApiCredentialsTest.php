<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\HRestApi\Common\ApiCredentials;

class ApiCredentialsTest extends TestCase
{
    protected static $apiToken = "";

    public static function setUpBeforeClass(): void
    {
        Dotenv\Dotenv::createImmutable(__DIR__ . "/../../")->load();
        self::$apiToken = getenv('HERE_API_KEY');
    }



    public function testLoadAccessToken()
    {

        $this->assertIsString(self::$apiToken, "Checking  Access token");
    }

    public function testCredential()
    {


        $credential = new ApiCredentials(self::$apiToken);
        $this->assertEquals(self::$apiToken, $credential->getAccessToken(), "Checking Credential Access token");
    }
}
