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
    private ?int $paramLimit = null;

    public const PARAM_OWNER_ME = "me";
    public const PARAM_OWNER_ID = "someother";
    public const PARAM_OWNER_OTHERS = "others";
    public const PARAM_OWNER_ALL = "*";


    public function __construct()
    {
        $this->reset();
    }

    public static function instance($xyzToken = ""):XyzSpace
    {
        $space = XyzSpace::config(XyzConfig::getInstance($xyzToken));
        return $space;
    }

    public static function config(XyzConfig $c):XyzSpace
    {
        $space = new XyzSpace();
        $space->c = $c;
        return $space;
    }

    public static function setToken(string $token):XyzSpace
    {
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
        $this->paramLimit = null;
    }


    public function update($spaceId, $obj)
    {
        $this->httpPatch();
        $this->spaceId($spaceId);
        $this->setType(self::API_TYPE_SPACEUPDATE);
        $this->requestBody = json_encode($obj);
        return  $this->getResponse();
    }

    public function create($title, $description)
    {
        $this->httpPost();
        $this->setType(self::API_TYPE_SPACECREATE);
        $this->requestBody = json_encode((object) [
            'title' => $title,
            'description' => $description
        ]);
        return  $this->getResponse();
    }

    public function delete($spaceId)
    {
        $this->httpDelete();
        $this->setType(self::API_TYPE_SPACEDELETE);
        $this->spaceId($spaceId);
        return  $this->getResponse();
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
     * Set limit of response.
     * NOT IMPLEMENTED BY API, use getLimited instead.
     * @return $this
     */
    public function limit(int $limit): XyzSpace
    {
        $this->paramLimit = $limit;
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
        return $this->owner(self::PARAM_OWNER_ID, $ownerId);
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

    /**
     * Set the space id in the API
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id): XyzSpace
    {
        $this->spaceId = $id;
        $this->setType(self::API_TYPE_SPACEDETAIL);
        return $this;
    }



    protected function queryString(): string
    {
        $retString = "";

        if ($this->paramIncludeRights) {
            $retString = $this->addQueryParam($retString, "includeRights", "true");
        }

        /* not used */
        if ($this->paramLimit) {
            $retString = $this->addQueryParam($retString, "limit", $this->paramLimit);
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


    public function getLimited($limit)
    {
        $array = $this->get();
        return array_slice($array, 0, $limit);
    }
}
