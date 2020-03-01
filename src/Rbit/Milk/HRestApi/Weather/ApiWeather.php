<?php


namespace Rbit\Milk\HRestApi\Weather;


use Rbit\Milk\HRestApi\Common\ApiClient;
use Rbit\Milk\HRestApi\Common\ApiConfig;

/**
 * Class ApiWeather
 * @package Rbit\Milk\HRestApi\Weather
 */
class ApiWeather extends ApiClient
{
    private string $format = "json";
    private string $paramProduct = "observation";
    private string $paramName = "Berlin";



    private const ENV_WEATHER = "ENV_WEATHER_PROD";

    // current weather conditions from the eight closest locations to the specified location
    private const PRODUCT_OBSERVATION = "observation";
    // morning, afternoon, evening and night weather forecasts for the next seven days.
    private const PRODUCT_FORECAST_7DAYS = "forecast_7days";
    // daily weather forecasts for the next seven days
    private const PRODUCT_FORECAST_7DAYS_SIMPLE = "forecast_7days_simple";
    // hourly weather forecasts for the next seven days
    private const PRODUCT_FORECAST_HOURLY = "forecast_hourly";
    // information on when the sun and moon rise and set, and on the phase of the moon for the next seven days
    private const PRODUCT_FORECAST_ASTRONOMY = "forecast_astronomy";
    // forecasted weather alerts for the next 24 hours
    private const PRODUCT_ALERTS = "alerts";
    // all active watches and warnings for the US and Canada
    private const PRODUCT_NWS_ALERTS = "nws_alerts";







    public function __construct()
    {
        $this->reset();
    }

    public static function instance($apiToken = ""): self
    {
        $hostname = "https://weather.ls.hereapi.com";
        $weather = ApiWeather::config(ApiConfig::getInstance($apiToken, $hostname, self::ENV_WEATHER));
        return $weather;
    }

    public static function config(ApiConfig $c): self
    {
        $weather = new ApiWeather();
        $weather->c = $c;
        return $weather;
    }

    public static function setToken(string $token): self
    {
        $weather = self::config(ApiConfig::getInstance());
        $weather->c->setToken($token);
        return $weather;
    }

    public function reset()
    {
        parent::reset();
        $this->acceptContentType = "*";
        $this->contentType = "*";
        $this->format = "json";
        $this->paramName = "Berlin";
        $this->paramProduct ="observation";

    }


    public function get()
    {
        $this->httpGet();
        return  $this->getResponse();
    }

    /**
     * Set the product for the Weather API
     * A parameter identifying the type of report to obtain.
     * The possible values are as follows:
     * - observation – current weather conditions from the eight closest locations to the specified location
     * - forecast_7days – morning, afternoon, evening and night weather forecasts for the next seven days.
     * - forecast_7days_simple – daily weather forecasts for the next seven days
     * - forecast_hourly – hourly weather forecasts for the next seven days
     * - forecast_astronomy – information on when the sun and moon rise and set, and on the phase of the moon for the next seven days
     * - alerts – forecasted weather alerts for the next 24 hours
     * - nws_alerts – all active watches and warnings for the US and Canada
     * @return $this
     */
    public function product(string $product): self
    {
        $this->paramProduct = $product;
        return $this;
    }

    public function name(string $name): self
    {
        $this->paramName = $name;
        return $this;
    }


    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramProduct) {
            $retString = $this->addQueryParam($retString, "product", $this->paramProduct);
        }

        if ($this->paramName) {
            $retString = $this->addQueryParam($retString, "name", $this->paramName);
        }

        $retString = $this->addQueryParam($retString, "apiKey", $this->c->getCredentials()->getAccessToken());


        return $retString;
    }

    protected function getPath(): string
    {
        $retPath= "";
        $retPath = "/weather/1.0/report.". $this->format;
        return $retPath;
    }



}
