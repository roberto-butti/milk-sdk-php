# Milk SDK PHP

[![Actions Status](https://github.com/roberto-butti/milk-sdk-php/workflows/PHP%20Composer/badge.svg)](https://github.com/roberto-butti/milk-sdk-php/actions)
[![GitHub license](https://img.shields.io/github/license/roberto-butti/milk-sdk-php)](https://github.com/roberto-butti/milk-sdk-php/blob/master/LICENSE.md)

Milk SDK PHP is a (fluent) open-source PHP library that makes it easy to integrate your PHP application with location services like XYZ Hub API, Geocoder API, ...

## Getting Started

### Install the SDK

In your PHP project install package via composer:

```sh
composer require rbit/milk-sdk-php
```
### Configuring XYZ HUB

With this SDK you can consume XYZ API.
You have 2 options:
- use an your own instance of XYZ HUB
or
- use XYZ HUB Cloud

#### Configure SDK with your own instance of XYZ HUB

Running your own instance of XYZ HUB means that you already have your instance of https://github.com/heremaps/xyz-hub.
A tutorial about how to setup XYZ Hub locally (on localhost): https://dev.to/robertobutti/restful-web-service-for-geospatial-data-12ii

Create a _.env_ file.
Fill it with:

```
XYZ_ACCESS_TOKEN=""
XYZ_API_HOSTNAME="http://localhost:8080"
```

#### Configure SDK with XYZ HUB Cloud service

Using XYZ HUB Cloud Service means that you are using this host https://xyz.api.here.com.

To use this service you need to sign in as developer on https://developer.here.com/ and create your plan (for exampole Feemium) and obtain your Access Token.

Once you have your access token, create a _.env_ file. You can start from a sample file (_.env.dist_):

```sh
cp .env.dist .env
```

Then, you need to fill your XYZ_ACCESS_TOKEN in .env file with your access token.

## Quick Examples

In order to use the Milk SDK, you need to:

- create a PHP file
- include the autoload.php file
- declare all imports via _use_
- load environment configuration (via _Dotenv_)
- get your token
- get your XYZ Spaces
- display your result

```php
<?php
// include the autoload.php file
require "./vendor/autoload.php";
// declare all imports via "use"
use Rbit\Milk\Xyz\Space\XyzSpace;
// load environment configuration (via Dotenv)
Dotenv\Dotenv::createImmutable(__DIR__)->load();
// get your Token
$xyzToken = getenv("XYZ_ACCESS_TOKEN");
// Get your XYZ Spaces
$s = XyzSpace::instance($xyzToken)->get();
// display your result
var_dump($s);
```

### Retrieve your XYZ Spaces

To get your XYZ Spaces:

```php
$s = XyzSpace::instance($xyzToken)->get();
```

To get XYZ Spaces by everybody (not only your own XYZ Spaces):

```php
$s =  XyzSpace::instance($xyzToken)->ownerAll()->get();
```

### Delete Space

To delete a XYZ Space:

```php
$xyzSpaceDeleted = XyzSpace::instance($xyzToken)->delete($spaceId);
```

### Create Space

To create a new XYZ Space:

```php
$xyzSpaceCreated = XyzSpace::instance($xyzToken)->create("My Space", "Description");
```

### Update Space

To update the XYZ Space with space id == \$spaceId:

```php
$obj = new \stdClass;
$obj->title = "Edited Title";
$obj->description = "Edited Description";
$retVal = $space->update($spaceId, $obj);
```

### Statistics

The get statistics from XYZ Space:

```php
$statistics =  XyzSpaceStatistics::instance($xyzToken)->spaceId($spaceId)->get();
```

### Features

Iterate features

```php
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = new XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->iterate($spaceId)->get();
```

### Retrieve 1 Feature

You need to use feature() method with $featureId and $spaceId

```php
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken);
$result = $xyzSpaceFeature->feature($featureId, $spaceId)->get();
```

### Create or Edit 1 Feature

To create or edit a Feature you can use saveOne() method.


```php
$spaceId = "yourspaceid";
$featureId = "yourfeatureid";
$geoJson = new GeoJson();
$properties = [
    "name" => "Berlin",
    "op" => "Put"
];
$geoJson->addPoint(52.5165, 13.37809, $properties, $featureId);
$feature = XyzSpaceFeatureEditor::instance($xyzToken);
$result = $feature->feature($geoJson->get())->saveOne($spaceId, $featureId);
$feature->debug();
```


### Search features by property

To search features by properties you can use *addSearchParams* to add serach params, in the example below, you are searching features with *name* property equals "Colosseo".

```php
$spaceId = "yourspaceid";
$xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken)->addSearchParams("p.name", "Colosseo");
$result = $xyzSpaceFeature->search($spaceId)->get();
```
### Search features by proximity

To search feature close to latitude=41.890251 and longitude=12.492373 with a radius less than 1000 meters (close to Colosseum):

```php
$spaceId = "yourspaceid";
$result = XyzSpaceFeature::instance($xyzToken)->spatial($spaceId,  41.890251, 12.492373,  1000)->get();
```

## Useful reference

ReDoc API documentation:
https://xyz.api.here.com/hub/static/redoc/

Open API documentation:
https://xyz.api.here.com/hub/static/swagger/
