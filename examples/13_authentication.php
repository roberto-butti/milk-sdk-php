<?php

require __DIR__ . "/../vendor/autoload.php";

use Rbit\Milk\HRestApi\Common\ApiAuth;
use Rbit\Milk\HRestApi\Weather\ApiWeather;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$keyId = getenv('HERE_API_KEY_ID');
$keySecret = getenv('HERE_API_KEY_SECRET');

$myToken= ApiAuth::getAccessToken($keyId, $keySecret);
$jsonWeather = ApiWeather::instance($myToken)
    ->productForecast7days()
    ->name("Berlin")
    ->getJson();

var_dump($jsonWeather);
