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
    private array $featureIds = [];
    private ?int $paramLimit = null;
    private array $paramSelection = [];
    private bool $paramSkipCache = false;
    private string $paramHandle = "";





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

        $this->contentType = "application/geo+json";
        $this->featureId = "";
        $this->spaceId = "";
        $this->paramLimit = null;
        $this->paramSelection = [];
        $this->paramSkipCache = false;
        $this->paramHandle = "";
        $this->featureIds = [];
    }

    public function iterate($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_ITERATE);
        return $this;
    }

    public function features($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURES);
        return $this;
    }

    public function feature($featureId, $spaceId= ""): XyzSpaceFeature
    {
        if ($spaceId !== "") {
            $this->spaceId = $spaceId;
        }
        $this->featureId = $featureId;
        $this->contentType = "application/geo+json";
        $this->setType(self::API_TYPE_FEATURE_DETAIL);
        return $this;
    }

    public function featureIds(array $featureIds): XyzSpaceFeature
    {
        $this->featureIds = $featureIds;
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

    /**
     * Set the limit in the API
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): XyzSpaceFeature
    {
        $this->paramLimit = $limit;
        return $this;
    }

    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramSkipCache) {
            $retString = $this->addQueryParam($retString, "skipCache", "true");
        }
        if ($this->paramLimit) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
        }
        if ($this->paramHandle) {
            $retString = $this->addQueryParam($retString, "handle", $this->paramHandle);
        }
        if (is_array($this->paramSelection) && count($this->paramSelection) > 0) {
            $retString = $this->addQueryParam($retString, "selection", implode(",", $this->paramSelection));
        }
        if (is_array($this->featureIds) && count($this->featureIds) > 0) {
            $retString = $this->addQueryParam($retString, "id", implode(",", $this->featureIds));
        }

        return $retString;
    }


    /**
     * If set to true the response is not returned from cache. Default is false.
     * @return $this
     */
    public function skipCache(bool $skipcache = true): XyzSpaceFeature
    {
        $this->paramSkipCache = $skipcache;
        return $this;
    }

    /**
     * List the properties you want to include in the response. If you have a property "title" and "description" you need to
     * use ["p.title", "p.description"].
     * @return $this
     */
    public function selection(array $propertiesList): XyzSpaceFeature
    {
        $this->paramSelection = $propertiesList;
        return $this;
    }


    /**
     * List the properties you want to include in the response. If you have a property "title" and "description" you need to
     * use ["p.title", "p.description"].
     * @return $this
     */
    public function handle(string $handle): XyzSpaceFeature
    {
        $this->paramHandle = $handle;
        return $this;
    }

    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    public function getPath():string
    {
        if ($this->featureId != "") {
            $this->uri = str_replace("{featureId}", $this->featureId, $this->uri);
        }
        echo parent::getPath();
        return parent::getPath();
        //return $retUrl;
    }
}
