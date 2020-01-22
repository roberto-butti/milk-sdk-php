<?php
use Rbit\Milk\Main;
use PHPUnit\Framework\TestCase;

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
        $xyzCryptoSecret =  getenv('XYZ_CRYPTO_SECRET');
        $xyzEncryptedToken =  getenv('XYZ_ENCRIPTED_ACCESS_TOKEN');


        $credential = new \Rbit\Milk\Xyz\XyzCredentials($xyzToken);
        $this->assertEquals($xyzToken, $credential->getAccessToken(), "Checking Credential Access token");


        //print_r($xyzSpace->httpGet()->includeRights()->get());
        //$credential = new \Rbit\Milk\Xyz\XyzCredentials("", $xyzCryptoSecret,  $xyzEncryptedToken);
        //$this->assertEquals($xyzToken, $credential->getAccessToken());





    }
}