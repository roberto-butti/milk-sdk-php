# milk-sdk-php

## Getting MILK SDK PHP

### Clone Sources (optional)

```sh
git clone https://github.com/roberto-butti/milk-sdk-php.git
cd milk-sdk-php
composer install
```

### Install Package
In your PHP projectInstall package via cmoposer:

```sh
composer require rbit/milk-sdk-php
```

### Obtain Access Token

Obtain your ACCESS TOKEN from https://developer.here.com/

Create a *.env* file. You can start from a sample file (*.env.dist*):
```sh
cp .env.dist .env
```

Then, you need to fill your XYZ_ACCESS_TOKEN in .env file with your access token.


## Say Hello World!

In order to use the Milk SDK, you need to:
- create a PHP file
- include the autoload.php file
- declare all imports via *use*
- load environment configuration (via *Dotenv*)
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
// Ggt your XYZ Spaces
$s = XyzSpace::setToken($xyzToken)->get();
// display your result
var_dump($s);
```



## Start to play with XYZ Spaces


### Spaces
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

## Useful reference

ReDoc API documentation:
https://xyz.api.here.com/hub/static/redoc/

Open API documentation:
https://xyz.api.here.com/hub/static/swagger/
