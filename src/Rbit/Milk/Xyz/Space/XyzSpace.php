<?php


namespace Rbit\Milk\Xyz\Space;

use GuzzleHttp\Exception\RequestException;
use Rbit\Milk\Xyz\Common\XyzClient;
use Rbit\Milk\Xyz\Common\XyzConfig;

/**
 * Class XyzSpace
 * @package Rbit\Milk\Xyz
 */
class XyzSpace extends XyzClient
{


    private bool $paramIncludeRights = false;
    private string $paramOwner = "";
    private string $paramOwnerId = "";

    public const PARAM_OWNER_ME = "me";
    public const PARAM_OWNER_ID = "someother";
    public const PARAM_OWNER_OTHERS = "others";
    public const PARAM_OWNER_ALL = "*";


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

        $this->paramIncludeRights = false;
        $this->paramOwner = "";
        $this->paramOwnerId = "";
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
        $this->paramIncludeRights = true;
        return $this;
    }

    /**
     * Define the owner(s) of spaces to be shown in the response.
     * @return $this
     */
    public function owner($owner = self::PARAM_OWNER_ME, $ownerId = ""): XyzSpace
    {
        if ($owner === self::PARAM_OWNER_ID) {
            $this->paramOwner = $owner;
            $this->paramOwnerId = $ownerId;
        } else {
            $this->paramOwner = $owner;
            $this->paramOwnerId = "";
        }
        return $this;
    }

    /**
     * Show only the spaces being owned by the current user.
     * @return $this
     */
    public function ownerMe(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ME);
    }

    /**
     * Only for shared spaces: Explicitly only show spaces belonging to the specified user.
     * @return $this
     */
    public function ownerSomeOther($ownerId): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ID,  $ownerId);
    }

    /**
     * Show only the spaces having been shared excluding the own ones.
     */
    public function ownerOthers(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_OTHERS);
    }

    /**
     * Show all spaces the current user has access to.
     * @return $this
     */
    public function ownerAll(): XyzSpace
    {
        return $this->owner(self::PARAM_OWNER_ALL);
    }


    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramIncludeRights) {
            $retString = $this->addQueryParam($retString, "includeRights", "true");
        }

        if ($this->paramOwner != "") {
            if ($this->paramOwner !== self::PARAM_OWNER_ID) {
                $retString = $this->addQueryParam($retString, "owner", $this->paramOwner);
            } else {
                $retString = $this->addQueryParam($retString, "owner", $this->paramOwnerId);
            }
        }


        return $retString;
    }
}