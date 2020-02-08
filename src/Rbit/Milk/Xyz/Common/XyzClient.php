<?php


namespace Rbit\Milk\Xyz\Common;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

use Rbit\Milk\Xyz\Space\XyzSpace;

use Kevinrob\GuzzleCache\CacheMiddleware;
use League\Flysystem\Adapter\Local;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;

abstract class XyzClient
{
    protected XyzConfig $c;
    protected $uri;
    protected $contentType;
    protected $requestBody = null;
    protected $spaceId = "";
    private $method;

    private $cacheResponse = true;

    const API_PATH_SPACES = "/hub/spaces";
    const API_PATH_FEATURES = "/hub/spaces/{spaceId}/features";
    const API_PATH_STATISTICS = "/hub/spaces/{spaceId}/statistics";
    const API_PATH_ITERATE = "/hub/spaces/{spaceId}/iterate";
    const API_PATH_SPACEDETAIL = "/hub/spaces/{spaceId}";
    const API_PATH_SPACEUPDATE = "/hub/spaces/{spaceId}";
    const API_PATH_SPACEDELETE = "/hub/spaces/{spaceId}";

    protected const API_TYPE_SPACES = "SPACES";
    protected const API_TYPE_FEATURES = "FEATURES";
    protected const API_TYPE_STATISTICS = "STATISTICS";
    protected const API_TYPE_ITERATE = "ITERATE";
    protected const API_TYPE_SPACEDETAIL = "SPACEDETAIL";
    protected const API_TYPE_SPACECREATE = "SPACE_CREATE";
    protected const API_TYPE_SPACEUPDATE = "SPACE_UPDATE";
    protected const API_TYPE_SPACEDELETE = "SPACE_DELETE";

    protected $apiHostPaths = [
        self::API_TYPE_SPACES => self::API_PATH_SPACES,
        self::API_TYPE_FEATURES => self::API_PATH_FEATURES,
        self::API_TYPE_STATISTICS => self::API_PATH_STATISTICS,
        self::API_TYPE_ITERATE => self::API_PATH_ITERATE,
        self::API_TYPE_SPACEDETAIL => self::API_PATH_SPACEDETAIL,
        self::API_TYPE_SPACECREATE => self::API_PATH_SPACES,
        self::API_TYPE_SPACEUPDATE => self::API_PATH_SPACEUPDATE,
        self::API_TYPE_SPACEDELETE => self::API_PATH_SPACEDELETE
    ];

    protected string $apiType;

    /**
     * Return the query string based on value setted by the user
     *
     * @return string
     */
    abstract protected function queryString(): string;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Show and list in console the current attributes of the object (spaceId, Url, content type etc)
     *
     * @return void
     */
    public function debug(): void
    {
        echo "CLIENT : " . PHP_EOL;
        echo "=========" . PHP_EOL;
        echo "URL    : " . $this->getUrl(). PHP_EOL;
        echo "METHOD : " . $this->method. PHP_EOL;
        echo "SPA ID : " . $this->spaceId. PHP_EOL;
        echo "C TYPE : " . $this->contentType. PHP_EOL;
        echo "API    : " . $this->apiType. PHP_EOL;
        echo "TOKEN  : " . $this->c->getCredentials()->getAccessToken(). PHP_EOL;
        var_dump($this->requestBody);
        echo "=========" . PHP_EOL;
    }

    /**
     * Function to reset all attributes as default (Reset Object to Factory settings)
     *
     * @return void
     */
    protected function reset()
    {
        $this->uri = "";
        $this->contentType = "application/json";
        $this->method = "GET";
        $this->apiType = self::API_TYPE_SPACES;
        $this->cacheResponse = true;
        $this->requestBody = null;
    }

    /**
     * Set the type of Endpoint developer wants to call- It is used to remember the endpoint AND set the correct path of endpoint
     * The type could be one of self::API_TYPE_*
     * @param string $apiType
     * @return void
     */
    protected function setType(string $apiType)
    {
        $this->apiType = $apiType;
        $this->uri = $this->apiHostPaths[$apiType];
    }

    /**
     * Set the HTTP Method for the endpoint ("GET", "POST", "DELETE", "PATCH")
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
     * Set the PATCH method
     * @return $this
     */
    public function httpPatch()
    {
        return $this->method("PATCH");
    }

    /**
     * Set the DELETE method
     * @return $this
     */
    public function httpDelete()
    {
        return $this->method("DELETE");
    }

    /**
     * Define if switch on or of caching response from APIs
     *
     * @param boolean $wannaCache
     * @return XyzClient
     */
    public function cacheResponse(bool $wannaCache): XyzClient
    {
        $this->cacheResponse = $wannaCache;
        return $this;
    }

    /**
     * @param $url, the URL (or the path) to add the new $name parameter with the value $value
     * @param $name
     * @param $value
     */
    protected function addQueryParam(string $url, string $name, $value): string
    {
        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . urlencode($name) . '=' . urlencode($value);
        return $url;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse()
    {
        $res = false;
        try {
            $res = $this->call($this->getUrl(), $this->contentType, $this->method, $this->requestBody);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            } else {
                $res = (object) [
                    "message" => $e->getMessage(),
                    "code" => $e->getCode()
                ];
            }
        } catch (Exception $e) {
            //echo $res->getStatusCode();
        }
        return $res;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        //echo $this->getUrl() . PHP_EOL;
        if ($this->cacheResponse) {
            $cache_tag = md5($this->getUrl() . $this->contentType . $this->method);
            $file_cache = "./cache/".$cache_tag;
            if (file_exists($file_cache)) {
                $content = file_get_contents($file_cache);
            } else {
                $content = $this->getResponse()->getBody();
                file_put_contents($file_cache, $content);
            }
        } else {
            $content = $this->getResponse()->getBody();
        }
        return json_decode($content);
    }

    /**
     * Return the URL of the API, replacing the placeholder with real values.
     * For example if spaceId is 12345 the Url for Space statistics is /spaces/12345/statistics
     *
     * @return string
     */
    public function getPath():string
    {
        $retUrl = self::API_PATH_SPACES;
        if ($this->spaceId != "") {
            $retUrl = str_replace("{spaceId}", $this->spaceId, $this->uri);
        }
        $queryParams = $this->queryString();
        if ($queryParams !== "") {
            $retUrl = $retUrl . $queryParams;
        }
        return $retUrl;
    }

    public function getUrl():string
    {
        return $this->c->getHostname() . $this->getPath();
    }


    public function call($uri, $contentType= 'application/json', $method, $body = null)
    {
        $client = new Client();

        $headers = [
            'User-Agent' => 'milk-sdk-php/0.1.0',
            'Accept'     => $contentType,
            'Authorization' => "Bearer {$this->c->getCredentials()->getAccessToken()}"
        ];
        if ($method === "POST") {
            $headers['Content-Type'] = "application/json";
        }
        if ($method === "PATCH") {
            $headers['Content-Type'] = "application/json";
        }
        $requestOptions=[
            //'debug' => true,
            'headers' => $headers
        ];
        if (! is_null($body)) {
            $requestOptions["body"] = $body;
        }


        $res = $client->request($method, $this->getUrl(), $requestOptions);
        //echo $res->getStatusCode();
        //echo $res->getBody();
        return $res;
    }



    protected function getConfig():XyzConfig
    {
        return $this->c;
    }
}
