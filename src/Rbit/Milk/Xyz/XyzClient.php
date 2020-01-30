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
    protected XyzConfig $c;
    protected $uri;
    protected $contentType;
    protected $spaceId = "";
    private $method;

    const API_PATH_SPACES = "/hub/spaces";
    const API_PATH_FEATURES = "/hub/spaces/{spaceId}/features";
    const API_PATH_STATISTICS = "/hub/spaces/{spaceId}/statistics";
    const API_PATH_ITERATE = "/hub/spaces/{spaceId}/iterate";
    const API_PATH_SPACEDETAIL = "/hub/spaces/{spaceId}";

    protected const API_TYPE_SPACES = "SPACES";
    protected const API_TYPE_FEATURES = "FEATURES";
    protected const API_TYPE_STATISTICS = "STATISTICS";
    protected const API_TYPE_ITERATE = "ITERATE";
    protected const API_TYPE_SPACEDETAIL = "SPACEDETAIL";

    protected $apiHostPaths = [
        self::API_TYPE_SPACES => self::API_PATH_SPACES,
        self::API_TYPE_FEATURES => self::API_PATH_FEATURES,
        self::API_TYPE_STATISTICS => self::API_PATH_STATISTICS,
        self::API_TYPE_ITERATE => self::API_PATH_ITERATE,
        self::API_TYPE_SPACEDETAIL => self::API_PATH_SPACEDETAIL
    ];

    protected string $apiType;


    public function __construct()
    {
        $this->reset();
    }

    protected function reset()
    {
        $this->uri = "";
        $this->contentType = "application/json";
        $this->method = "GET";
        $this->apiType = self::API_TYPE_SPACES;
    }

    protected function setType($apiType)
    {
        $this->apiType = $apiType;
        $this->uri = $this->apiHostPaths[$apiType];
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
        $cache_tag = md5($this->getUrl() . $this->contentType . $this->method);
        $file_cache = "./cache/".$cache_tag;
        if (file_exists($file_cache)) {
            $content = file_get_contents($file_cache);
        } else {
            $content = $this->getResponse()->getBody();
            file_put_contents($file_cache, $content);
        }
        return json_decode($content);
    }

    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    protected function getUrl():string
    {
        $retUrl = self::API_PATH_SPACES;
        if ($this->spaceId != "") {
            $retUrl = str_replace("{spaceId}", $this->spaceId, $this->uri);
        }
        return $retUrl;
    }


    public function call($uri, $contentType= 'application/json', $method)
    {
        $client = new Client();
        $res = $client->request($method, $this->c->getHostname() . $this->getUrl(), [
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
        $this->setType(self::API_TYPE_SPACEDETAIL);
        return $this;
    }

    protected function getConfig():XyzConfig
    {
        return $this->c;
    }
}
