<?php


namespace Rbit\Milk\Xyz;

use GuzzleHttp\Exception\RequestException;

/**
 * Class XyzSpace
 * @package Rbit\Milk\Xyz
 */
class XyzSpace extends XyzClient
{


    private bool $includeRights = false;


    public function __construct()
    {
        $this->reset();
    }

    public static function instance():XyzSpace {
        $space = XyzSpace::config(XyzConfig::getInstance());
        return $space;
    }

    public static function config(XyzConfig $c):XyzSpace {
        $space = new XyzSpace();
        $space->c = $c;
        return $space;
    }

    public static function setToken(string $token):XyzSpace {
        $space = XyzSpace::config(XyzConfig::getInstance());
        $space->c->setToken($token);
        return $space;
    }

    public function reset()
    {
        parent::reset();

        $this->includeRights = false;
        $this->spaceId = "";
    }

    public function statistics(): XyzSpace
    {
        $this->setType(self::API_TYPE_STATISTICS);
        $this->contentType= "application/json";
        return $this;
    }

    /**
     * The access rights for each space are included in the response.
     * @return $this
     */
    public function includeRights(): XyzSpace
    {
        $this->addQueryParam("includeRights", "true");
        return $this;
    }

    /**
     * Define the owner(s) of spaces to be shown in the response.
     * @return $this
     */
    public function owner($owner = "me"): XyzSpace
    {
        $this->addQueryParam("owner", $owner);
        return $this;
    }

    /**
     * Show only the spaces being owned by the current user.
     * @return $this
     */
    public function ownerMe(): XyzSpace
    {
        return $this->owner("owner");
    }

    /**
     * Only for shared spaces: Explicitly only show spaces belonging to the specified user.
     * @return $this
     */
    public function ownerSomeOther($ownerId): XyzSpace
    {
        return $this->owner($ownerId);
    }

    /**
     * Show only the spaces having been shared excluding the own ones.
     */
    public function ownerOthers(): XyzSpace
    {
        return $this->owner("others");
    }

    /**
     * Show all spaces the current user has access to.
     * @return $this
     */
    public function ownerAll(): XyzSpace
    {
        return $this->owner("*");
    }
}
