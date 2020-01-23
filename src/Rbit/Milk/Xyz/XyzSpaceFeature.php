<?php


namespace Rbit\Milk\Xyz;

use GuzzleHttp\Exception\RequestException;

/**
 * Class XyzSpaceFeature
 * @package Rbit\Milk\Xyz
 */
class XyzSpaceFeature extends XyzClient
{

    const API_SPACES_FEATURES = "/hub/spaces/{spaceId}/features";
    const API_SPACES_ITERATE = "/hub/spaces/{spaceId}/iterate";
    const API_SPACES_STATISTICS = "/hub/spaces/{spaceId}/statistics";
    private string $spaceId ="";
    private string $featureId ="";


    public function __construct(XyzConfig $c)
    {
        parent::__construct($c);

        $this->reset();
    }

    public function reset()
    {
        parent::reset();
        $this->uri = self::API_SPACES_FEATURES;
        $this->contentType = "application/geo+json";
        $this->featureId = "";
        $this->spaceId = "";
    }


    /**
     * Set the space id in the API
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id): XyzSpaceFeature
    {
        $this->spaceId = $id;
        $this->uri = str_replace("{spaceId}", $this->spaceId, $this->uri);
        return $this;
    }

    private function switchUrl($url)
    {
        $this->uri = $url;
        if ($this->spaceId != "") {
            $this->uri = str_replace("{spaceId}", $this->spaceId, $this->uri);
        }
    }

    public function statistics(): XyzSpaceFeature
    {
        $this->switchUrl(self::API_SPACES_STATISTICS);
        $this->contentType= "application/json";
        return $this;
    }

    public function iterate(): XyzSpaceFeature
    {
        $this->switchUrl(self::API_SPACES_ITERATE);
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


}
