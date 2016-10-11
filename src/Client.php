<?php

namespace ShareCloth\Api;


use BadResponseException;
use GuzzleHttp\Psr7\Response;

class Client implements ClientInterface
{

    /** @var  \GuzzleHttp\Client */
    protected $httpClient;

    /** @var  string */
    protected $apiSecret;

    /** @var  string */
    protected $client;

    /** @var string  */
    protected $baseUri = 'http://api.sharecloth.com/v1/';

    /**
     * Client constructor.
     * @param $email
     * @param $password
     * @param array $httpClientConfig
     *
     * @throws BadResponseException
     */
    public function __construct($email, $password, $httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
        $response = $this->makeRequest('user/login', [
            'email' => $email,
            'password' => $password
        ]);

        if ($response->getStatusCode() == 200) {
            $data = $this->parseResponse($response);

            $this->apiSecret = $data['data']['api_secret'];
            $this->client = $data['data']['id'];

        } else {
            throw new BadResponseException();
        }
    }

    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    public function itemsList($options)
    {
        $response = $this->makeRequest('items/list', $options);
        $data = $this->parseResponse($response);

        return $data;
    }

    public function avatarList($options)
    {
        $response = $this->makeRequest('avatar/list', $options);
        $data = $this->parseResponse($response);

        return $data;
    }

    protected function makeRequest($uri, $options, $method = 'POST')
    {
        if ($this->client && $this->apiSecret) {
            $options = array_merge($options, $this->generateAuthData());
        }
        return $this->httpClient->request($method, $uri, $options);
    }

    protected function initHttpClient($httpClientConfig)
    {
        $config = array_merge($httpClientConfig, [
            'base_uri' => $this->baseUri
        ]);

        $this->httpClient = new \GuzzleHttp\Client($config);
    }

    protected function parseResponse(Response $response)
    {
        $data = $response->getBody();
        return json_encode($data);
    }

    protected function generateAuthData()
    {

    }


}