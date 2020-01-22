<?php


namespace Rbit\Milk\Xyz;


class XyzConfig
{
    private static $instance = null;

    private XyzCredentials $credentials;
    private string $hostname;


    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return XyzCredentials
     */
    public function getCredentials(): XyzCredentials
    {
        return $this->credentials;
    }

    private function __construct()
    {
        $this->hostname = "https://xyz.api.here.com";
        /** @var Dotenv\Dotenv $dotenv */
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../../../");
        $dotenv->load();
        $xyzToken =  getenv('XYZ_ACCESS_TOKEN');
        $xyzCryptoSecret =  getenv('XYZ_CRYPTO_SECRET');
        $xyzEncryptedToken =  getenv('XYZ_ENCRIPTED_ACCESS_TOKEN');
        $credential = new \Rbit\Milk\Xyz\XyzCredentials($xyzToken);

        $this->credentials = $credential;
    }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new XyzConfig();
        }

        return self::$instance;
    }



}