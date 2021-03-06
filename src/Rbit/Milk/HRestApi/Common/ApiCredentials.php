<?php


namespace Rbit\Milk\HRestApi\Common;


class ApiCredentials
{
    private string $token;


    /**
     * Constructs a new ApiCredentials object, with the specified
     * access token
     *
     * @param string $token   Security token to use
     */
    public function __construct($token = "")
    {
        $this->token = $token;
    }

    public static function token($token): ApiCredentials
    {
        $credentials = new ApiCredentials($token);
        return $credentials;
    }



    public static function __set_state(array $state)
    {
        return new self(
            $state['token']
        );
    }
    public function getAccessToken()
    {
        return $this->token;
    }

    public function setAccessToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Return the Array representation of this object
     * [
     *   'token' => 'some token'
     * ]
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token'     => $this->token
        ];
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $this->token = $data['token'];
    }
}
