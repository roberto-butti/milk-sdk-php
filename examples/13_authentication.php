<?php

require __DIR__ . "/../vendor/autoload.php";


/**
 * https://developer.here.com/documentation/authentication/dev_guide/topics/token.html
 *
 * Thanks to clear documentation:
 * https://developer.here.com/documentation/authentication/dev_guide/topics/using-aaa-javasdk-or-3rd-party-libraries.html#using-aaa-javasdk-or-3rd-party-libraries
 * https://developer.twitter.com/en/docs/basics/authentication/oauth-1-0a/creating-a-signature
 *
 */
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

Dotenv\Dotenv::createImmutable(__DIR__ . "/../")->load();
$keyId = getenv('HERE_API_KEY_ID');
$keySecret = getenv('HERE_API_KEY_SECRET');

$httpBody = [
    "grant_type" => "client_credentials"
];
$httpMethod = "POST";
$httpUrl = 'https://account.api.here.com/oauth2/token';


$oauthNonce = mt_rand();
$oauthTimestamp = time();
$oauthSignatureMethod= "HMAC-SHA256";
$oauthVersion = "1.0";


$baseString = $httpMethod."&". urlencode($httpUrl);

$oauth1Param = [
    'oauth_consumer_key' => $keyId,
    'oauth_signature_method' => $oauthSignatureMethod,
    'oauth_timestamp' => $oauthTimestamp,
    'oauth_nonce' => $oauthNonce,
    'oauth_version' => $oauthVersion
];


$paramString =

        "grant_type=client_credentials&".
        "oauth_consumer_key=". urlencode($oauth1Param['oauth_consumer_key']).
        "&oauth_nonce=". urlencode($oauth1Param['oauth_nonce']).
        "&oauth_signature_method=". urlencode($oauth1Param['oauth_signature_method']).
        "&oauth_timestamp=". urlencode($oauth1Param['oauth_timestamp']).
//        "&oauth_token=".
        "&oauth_version=". urlencode($oauth1Param['oauth_version'])
;

echo $paramString.PHP_EOL;
$baseString = $baseString . "&" . urlencode($paramString);
echo $baseString . PHP_EOL;
$signingKey= urlencode($keySecret) . "&";
$signature = urlencode(
    base64_encode(
    hash_hmac(
        'sha256',
        $baseString,
        $signingKey,
        true
    )
    )
);

$oauth1Param['oauth_signature']  =  $signature;
echo "RUNTIME SIGNATURE : " . $signature .PHP_EOL;
var_dump($oauth1Param);

$headerOauth = "OAuth ";
$sep="";
foreach ($oauth1Param as $key => $value) {
    $headerOauth = $headerOauth.$sep.$key."=\"".$value."\"";
    $sep=",";
}
echo $headerOauth.PHP_EOL;


$client = new Client();
try {
    $res = $client->request($httpMethod, $httpUrl, [
        'form_params'=> $httpBody,
        'headers'  => [
            "Authorization"=> $headerOauth
        ]
    ]);
    echo $res->getStatusCode();
    echo $res->getBody();
    } catch (ClientException $e) {
    //echo Psr7\str($e->getRequest());
    echo Psr7\str($e->getResponse());
}

