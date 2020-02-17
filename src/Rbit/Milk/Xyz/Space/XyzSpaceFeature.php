<?php


namespace Rbit\Milk\Xyz\Space;

use Rbit\Milk\Xyz\Common\XyzConfig;
use Rbit\Milk\Xyz\Space\XyzSpaceFeatureBase;
use Rbit\Milk\Xyz\Common\XyzClient;
use stdClass;

/**
 * Class XyzSpaceFeature
 * @package Rbit\Milk\Xyz\Space
 */
class XyzSpaceFeature extends XyzSpaceFeatureBase
{
    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""):XyzSpaceFeature
    {
        $features = XyzSpaceFeature::config(XyzConfig::getInstance($xyzToken));
        return $features;
    }

    public static function config(XyzConfig $c):XyzSpaceFeature
    {
        $features = new XyzSpaceFeature();
        $features->c = $c;
        return $features;
    }

    public static function setToken(string $token):XyzSpaceFeature
    {
        $features = XyzSpaceFeature::config(XyzConfig::getInstance());
        $features->c->setToken($token);
        return $features;
    }

    public function reset()
    {
        parent::reset();
    }

    public function iterate($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_ITERATE);
        return $this;
    }

    public function features($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURES);
        return $this;
    }

    public function search($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->httpGet();
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_SEARCH);

        return $this;
    }

    public function spatial($spaceId, $latitude = null, $longitude=null, $radius = null): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        if (! is_null($latitude)) {
            $this->paramLatitude = $latitude;
        }
        if (!is_null($longitude)) {
            $this->paramLongitude = $longitude;
        }
        if (!is_null($radius)) {
            $this->paramRadius = $radius;
        }

        $this->httpGet();
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_GETSPATIAL);

        return $this;
    }

    public function feature($featureId, $spaceId= ""): XyzSpaceFeature
    {
        if ($spaceId !== "") {
            $this->spaceId = $spaceId;
        }
        $this->featureId = $featureId;
        $this->acceptContentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_DETAIL);
        return $this;
    }
}
