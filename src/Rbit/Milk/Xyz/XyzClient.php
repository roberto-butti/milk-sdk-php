<?php


namespace Rbit\Milk\Xyz;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

use Kevinrob\GuzzleCache\CacheMiddleware;
use League\Flysystem\Adapter\Local;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;

class XyzClient
{
    private XyzConfig $c;
    protected $uri;
    protected $contentType;
    protected $spaceId = "";
    private $method;

    const API_SPACES_FEATURES = "/hub/spaces/{spaceId}/features";
    const API_SPACES_ITERATE = "/hub/spaces/{spaceId}/iterate";
    const API_SPACES_STATISTICS = "/hub/spaces/{spaceId}/statistics";
    const API_SPACES = "/hub/spaces";
    const API_SPACES_DETAIL = "/hub/spaces/{spaceId}";

    public function __construct(XyzConfig $c)
    {
        $this->c = $c;
        $this->reset();
    }

    protected function reset()
    {
        $this->uri = "";
        $this->contentType = "application/json";
        $this->method = "GET";
    }

    public function setToken(string $token)
    {
        $this->c->getCredentials()->setAccessToken($token);
    }
    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method= $method;
        return $this;
    }

    /**
     * Set the GET method
     * @return $this
     */
    public function httpGet()
    {
        return $this->method("GET");
    }

    /**
     * Set the POST method
     * @return $this
     */
    public function httpPost()
    {
        return $this->method("POST");
    }

    /**
     * @param $name
     * @param $value
     */
    protected function addQueryParam($name, $value)
    {
        //$this->uri = $this->uri . "?" . $name . "=" .$value;
        $this->uri .= (parse_url($this->uri, PHP_URL_QUERY) ? '&' : '?') . urlencode($name) . '=' . urlencode($value);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse()
    {
        try {
            $res = $this->call($this->uri, $this->contentType, $this->method);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            }
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $cache_tag = md5($this->uri . $this->contentType . $this->method);
        $file_cache = "./cache/".$cache_tag;
        if (file_exists($file_cache)) {
            $content = file_get_contents($file_cache);
        } else {
            $content = $this->getResponse()->getBody();
            file_put_contents($file_cache, $content);
        }
        return json_decode($content);
    }

    protected function switchUrl($url)
    {
        $this->uri = $url;
        if ($this->spaceId != "") {
            $this->uri = str_replace("{spaceId}", $this->spaceId, $this->uri);
        }
    }


    public function call($uri, $contentType= 'application/json', $method)
    {
        $client = new Client();
        $res = $client->request($method, $this->c->getHostname() . $uri, [
            //'debug' => true,
            'headers' => [
                'User-Agent' => 'milk-sdk-php/0.1.0',
                'Accept'     => $contentType,
                'Authorization' => "Bearer {$this->c->getCredentials()->getAccessToken()}"
            ]
        ]);
        //echo $res->getStatusCode();
        //echo $res->getBody();
        return $res;
    }

    /**
     * Set the space id in the API
     * @param string $id
     * @return $this
     */
    public function spaceId(string $id): XyzSpace
    {
        $this->spaceId = $id;
        $this->switchUrl(self::API_SPACES_DETAIL);
        $this->uri = str_replace("{spaceId}", $this->spaceId, $this->uri);
        return $this;
    }
}
