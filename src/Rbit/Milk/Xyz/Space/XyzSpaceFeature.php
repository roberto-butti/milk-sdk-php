<?php


namespace Rbit\Milk\Xyz\Space;


use Rbit\Milk\Xyz\Common\XyzConfig;
use Rbit\Milk\Xyz\Common\XyzClient;

/**
 * Class XyzSpaceFeature
 * @package Rbit\Milk\Xyz\Space
 */
class XyzSpaceFeature extends XyzClient
{
    private string $featureId ="";


    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""):XyzSpaceFeature {
        $features = XyzSpaceFeature::config(XyzConfig::getInstance($xyzToken));
        return $features;
    }

    public static function config(XyzConfig $c):XyzSpaceFeature {
        $features = new XyzSpaceFeature();
        $features->c = $c;
        return $features;
    }

    public static function setToken(string $token):XyzSpaceFeature {
        $features = XyzSpaceFeature::config(XyzConfig::getInstance());
        $features->c->setToken($token);
        return $features;
    }

    public function reset()
    {
        parent::reset();
        $this->contentType = "application/geo+json";
        $this->featureId = "";
        $this->spaceId = "";
    }

    public function iterate($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->setType(self::API_TYPE_ITERATE);
        return $this;
    }

    /**
     * Set the feature id in the API
     * @param string $id
     * @return $this
     */
    public function featureId(string $id): XyzSpaceFeature
    {
        $this->featureId = $id;
        return $this;
    }

    protected function queryString(): string
    {
        $retString = "";



        return $retString;
    }

}
