# milk-sdk-php

## Getting MILK SDK PHP

### Clone Sources

```sh
git clone https://github.com/roberto-butti/milk-sdk-php.git
cd milk-sdk-php
composer install
```

Obtain your ACCESS TOKEN from https://developer.here.com/

```sh
cp .env.dist .env
```

Then, you need to fill your XYZ_ACCESS_TOKEN in .env file with your access token.

### Execute test suite

In order to check if everything is ok, try tu run the Test Suite:

```sh
make test
```


Create your .env file
## Start to play with XYZ Spaces

### Spaces
To get your XYZ Spaces:
```php
$s = XyzSpace::instance()->get();
```

To get XYZ Spaces by everybody (not only your own XYZ Spaces):
```php
$s =  XyzSpace::instance()->ownerAll()->get();
```

### Statistics
The get statistics from XYZ Space:
```php
$statistics =  XyzSpace::instance()->spaceId($spaceId)->statistics()->get();
```

### Features
Iterate features

```php
/** XyzSpaceFeature $xyzSpaceFeature */
$xyzSpaceFeature = new XyzSpaceFeature(XyzConfig::getInstance());
$result = $xyzSpaceFeature->iterate($spaceId)->get();
```

## Useful reference

ReDoc API documentation¶
https://xyz.api.here.com/hub/static/redoc/

Open API documentation¶
https://xyz.api.here.com/hub/static/swagger/
