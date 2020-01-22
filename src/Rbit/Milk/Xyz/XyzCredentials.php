<?php


namespace Rbit\Milk\Xyz;


class XyzCredentials
{
    private string $iv;
    private string $tokenEncrypted;
    private string $token;


    /**
     * Constructs a new XyzCredentials object, with the specified XYZ
     * access token
     *
     * @param string $key     AWS access key ID
     * @param string $secret  AWS secret access key
     * @param string $token   Security token to use
     */
    public function __construct($token = "", $iv = "", $tokenEncrypted = "")
    {
        $this->token = $token;
        $this->iv = $iv;
        $this->tokenEncrypted = $tokenEncrypted;

        if ($this->token === "") {
            $token = $this->decryptToken($tokenEncrypted, $iv);
        }

    }



    public function decryptToken($tokenEncrypted, $iv) {

        $retVal = openssl_decrypt($tokenEncrypted, AES_256_CBC, $encryption_key, 0, $iv);
        return $retVal;
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

    public function toArray()
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