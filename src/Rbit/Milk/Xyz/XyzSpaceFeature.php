<?php


namespace Rbit\Milk\Xyz;

use GuzzleHttp\Exception\RequestException;

/**
 * Class XyzSpaceFeature
 * @package Rbit\Milk\Xyz
 */
class XyzSpaceFeature extends XyzClient
{
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

    public function iterate($spaceId): XyzSpaceFeature
    {
        $this->spaceId = $spaceId;
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
