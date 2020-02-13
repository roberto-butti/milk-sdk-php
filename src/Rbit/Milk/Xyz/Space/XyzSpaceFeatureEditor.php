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

    protected array $paramAddTags = [];
    protected array $paramRemoveTags = [];


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
        $this->paramAddTags = [];
        $this->paramRemoveTags = [];

    }

    public function create($spaceId, $geojson)
    {
        $this->httpPut();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_CREATE);
        $this->requestBody = $geojson;
        return $this->getResponse();
    }


    public function edit($spaceId, $geojson)
    {
        $this->httpPost();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_EDIT);
        $this->requestBody = $geojson;
        return $this->getResponse();
    }

    public function delete($spaceId, array $featuresIds)
    {
        $this->httpDelete();
        $this->spaceId = $spaceId;
        $this->acceptContentType = "application/geo+json";
        $this->contentType = "application/geo+json";
        $this->paramFeatureIds = $featuresIds;
        $this->setType(self::API_TYPE_FEATURE_DELETE);
        $this->requestBody = null;
        return $this->getResponse();
    }


    public function deleteOne($spaceId, $featureId)
    {
        $this->httpDelete();
        $this->spaceId = $spaceId;
        $this->featureId = $featureId;
        $this->acceptContentType = "application/json";
        $this->contentType = "application/json";
        $this->paramFeatureIds = [];
        $this->setType(self::API_TYPE_FEATURE_DELETEONE);
        $this->requestBody = null;
        return $this->getResponse();
    }

    /**
     * Set the tags for feature creation
     * @param array $tags
     * @return $this
     */
    public function addTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramAddTags = $tags;
        return $this;
    }

    /**
     * Set the removing tags for feature editing
     * @param array $tags
     * @return $this
     */
    public function removeTags(array $tags): XyzSpaceFeatureEditor
    {
        $this->paramRemoveTags = $tags;
        return $this;
    }

    protected function queryString(): string
    {
        $retString = "";
        $retString = parent::queryString();

        if (is_array($this->paramAddTags) && count($this->paramAddTags) > 0) {
            $retString = $this->addQueryParam($retString, "addTags", implode(",", $this->paramAddTags));
        }

        if (is_array($this->paramRemoveTags) && count($this->paramRemoveTags) > 0) {
            $retString = $this->addQueryParam($retString, "removeTags", implode(",", $this->paramRemoveTags));
        }




        return $retString;
    }


}
