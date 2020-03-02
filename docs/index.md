# Milk SDK PHP

[![Actions Status](https://github.com/roberto-butti/milk-sdk-php/workflows/PHP%20Composer/badge.svg)](https://github.com/roberto-butti/milk-sdk-php/actions)
[![GitHub license](https://img.shields.io/github/license/roberto-butti/milk-sdk-php)](https://github.com/roberto-butti/milk-sdk-php/blob/master/LICENSE.md)

Milk SDK PHP is a (fluent) open-source PHP library that makes it easy to integrate your PHP application with location services like XYZ Hub API, Geocoder API, ...

## What you can do

Through a fluent interface you can develop applications, webapp, CLI, with PHP for:
- access and manage geolocation data in a cloud service XYZ Hub
- retrieve Weather informations (current weather conditions, alerts, forecast, sun and moon rise and set...)

## Example

```php
/**
 * Retrieve a weather forecast in Venezia, in italian language
 */
$responseJson = ApiWeather::instance("yourapykey")
    ->product("forecast_7days")
    ->name("Venezia")
    ->cacheResponse(true)
    ->language("it")
    ->getJson();
```
