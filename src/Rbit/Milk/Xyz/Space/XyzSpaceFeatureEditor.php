<?php


namespace Rbit\Milk\Xyz\Space;

use Rbit\Milk\Xyz\Common\XyzConfig;
use Rbit\Milk\Xyz\Space\XyzSpaceFeatureBase;
use Rbit\Milk\Xyz\Common\XyzClient;
use stdClass;

/**
 * Class XyzSpaceFeatureEditor
 * @package Rbit\Milk\Xyz\Space
 */
class XyzSpaceFeatureEditor extends XyzSpaceFeatureBase
{
    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""): XyzSpaceFeatureEditor
    {
        $features = XyzSpaceFeatureEditor::config(XyzConfig::getInstance($xyzToken));
        return $features;
    }

    public static function config(XyzConfig $c): XyzSpaceFeatureEditor
    {
        $features = new XyzSpaceFeatureEditor();
        $features->c = $c;
        return $features;
    }

    public static function setToken(string $token): XyzSpaceFeatureEditor
    {
        $features = XyzSpaceFeatureEditor::config(XyzConfig::getInstance());
        $features->c->setToken($token);
        return $features;
    }

    public function reset()
    {
        parent::reset();
    }

    public function create($spaceId, $geojson)
    {
        $this->httpPut();
        $this->spaceId = $spaceId;
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_CREATE);
        $this->requestBody = $geojson;
        return $this->getResponse();
    }


}
