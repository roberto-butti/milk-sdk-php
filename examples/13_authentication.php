<?php

require __DIR__ . "/../vendor/autoload.php";



Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$keyId = getenv('HERE_API_KEY_ID');
$keySecret = getenv('HERE_API_KEY_SECRET');



use Rbit\Milk\HRestApi\Common\ApiAuth;
use Rbit\Milk\HRestApi\Weather\ApiWeather;

echo PHP_EOL . "=======================" . PHP_EOL;
$json= ApiAuth::createSignature($keyId, $keySecret);
$myToken = json_decode($json)->access_token;
var_dump($myToken);

echo PHP_EOL."=======================" . PHP_EOL;
$jsonWeather = ApiWeather::instance($myToken)
    ->productForecast7days()
    ->name("Berlin")
    ->getJson();

var_dump($jsonWeather);
