<?php


namespace Rbit\Milk\Xyz;
use GuzzleHttp\Exception\RequestException;


/**
 * Class XyzSpace
 * @package Rbit\Milk\Xyz
 */
class XyzSpace extends XyzClient
{

    private $spaceId="";

    public function __construct(XyzConfig $c)
    {
        parent::__construct($c);
        $this->reset();

    }

    public function reset() {
        parent::reset();
        $this->uri = "/hub/spaces";
        $this->spaceId = "";

    }




    /**
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id) {
        $this->spaceId = $id;
        $this->uri = "/hub/spaces/".$id;
        return $this;
    }



    /**
     * The access rights for each space are included in the response.
     * @return $this
     */
    public function includeRights() {
        $this->addQueryParam("includeRights", "true");
        return $this;
    }

    /**
     * Define the owner(s) of spaces to be shown in the response.
     * @return $this
     */
    public function owner( $owner = "me") {
        $this->addQueryParam("owner", $owner);
        return $this;
    }

    /**
     * Show only the spaces being owned by the current user.
     * @return $this
     */
    public function ownerMe() {
        return $this->owner("owner");
    }

    /**
     * Only for shared spaces: Explicitly only show spaces belonging to the specified user.
     * @return $this
     */
    public function ownerSomeOther($ownerId) {
        return $this->owner($ownerId);
    }

    /**
     * Show only the spaces having been shared excluding the own ones.
     * @return $this
     */
    public function ownerOthers() {
        return $this->owner("others");
    }

    /**
     * Show all spaces the current user has access to.
     * @return $this
     */
    public function ownerAll() {
        return $this->owner("*");
    }









}