<?php
require __DIR__ . "/../vendor/autoload.php";

use \Rbit\Milk\HRestApi\Weather\ApiWeather;


Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$hereApiKey = getenv('HERE_API_KEY');


function print_row($item, $key)
{
    echo $key + 1 . " " . $item->id . " " . $item->owner . " " . $item->title . "\n";
}


$jsonWeather = ApiWeather::instance($hereApiKey)
                ->productForecast7days()
                ->name("Berlin")
                ->getJson();

var_dump($jsonWeather);
