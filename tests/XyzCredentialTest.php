<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\Xyz\Common\XyzCredentials;

class XyzCredentialTest extends TestCase
{



    public function testLoadAccessToken()
    {
        /** @var Dotenv\Dotenv $dotenv */
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $this->assertIsString($xyzToken, "Checking  Access token");
    }

    public function testCredential()
    {

        /** @var Dotenv\Dotenv $dotenv */

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
        $dotenv->load();
        $xyzToken =  getenv('XYZ_ACCESS_TOKEN');


        $credential = new XyzCredentials($xyzToken);
        $this->assertEquals($xyzToken, $credential->getAccessToken(), "Checking Credential Access token");


    }
}
