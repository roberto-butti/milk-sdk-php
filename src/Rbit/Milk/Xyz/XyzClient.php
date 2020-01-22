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
    private $contentType;
    private $method;


    public function __construct(XyzConfig $c)
    {
        $this->c = $c;
        $this->reset();
    }

    protected function reset() {
        $this->uri = "/";
        $this->contentType = "application/json";
        $this->method = "GET";
    }

    public function setToken(string $token) {
        $this->c->getCredentials()->setAccessToken($token);
    }
    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method) {
        $this->method= $method;
        return $this;
    }

    /**
     * Set the GET method
     * @return $this
     */
    public function httpGet() {
        return $this->method("GET");
    }

    /**
     * Set the POST method
     * @return $this
     */
    public function httpPost() {
        return $this->method("POST");
    }

    /**
     * @param $name
     * @param $value
     */
    protected function addQueryParam($name, $value) {
        //$this->uri = $this->uri . "?" . $name . "=" .$value;
        $this->uri .= (parse_url($this->uri, PHP_URL_QUERY) ? '&' : '?') . urlencode($name) . '=' . urlencode($value);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse() {
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
    public function get() {
        return json_decode($this->getResponse()->getBody());
    }


    public function call($uri, $contentType= 'application/json', $method) {
        $stack = HandlerStack::create();
        $stack->push(
            new CacheMiddleware(
                new PrivateCacheStrategy(
                    new FlysystemStorage(
                        new Local('/tmp/mycache')
                    )
                )
            ),
            'cache'
        );

        $client = new Client(['handler' => $stack]);
        $res = $client->request($method, $this->c->getHostname() . $uri, [
            //'debug' => true,
            'headers' => [
                'User-Agent' => 'php-app/1.0',
                'Accept'     => $contentType,
                'Authorization' => "Bearer {$this->c->getCredentials()->getAccessToken()}"
            ]
        ]);
        //echo $res->getStatusCode();
        //echo $res->getBody();
        return $res;
    }

}