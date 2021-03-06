<?php

namespace Jerodev\Diglett;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class WebClient
{
    /**
     *  The underlying goutte client.
     *
     *  @var Client
     */
    private static $goutteClient;

    /**
     *  Create a new Diglett instance.
     *
     *  @param GuzzleClient|array|null $client
     */
    public function __construct($client = null)
    {
        $goutteClient = new Client();

        if (is_array($client)) {
            $guzzleClient = new GuzzleClient($client);
            $goutteClient->setClient($guzzleClient);
        } elseif ($client instanceof GuzzleClient) {
            $goutteClient->setClient($client);
        } else {
            // Unknow parmeter type or null, use default configuration
            $this->getClient();
        }
    }

    /**
     *  Perform a GET request.
     *
     *  @param string $url
     */
    public static function get(string $url): Diglett
    {
        return self::request('GET', $url);
    }

    /**
     *  Perform a POST request.
     *
     *  @param string $url
     */
    public static function post(string $url): Diglett
    {
        return self::request('POST', $url);
    }

    /**
     *  Perform a web request.
     *
     *  @param string $method Http method
     *  @param string $url
     */
    private static function request(string $method, string $url): Diglett
    {
        return new Diglett(self::getClient()->request($method, $url));
    }

    /**
     *  Get the active GoutteClient.
     */
    private static function getClient(): Client
    {
        if (!isset(self::$goutteClient)) {
            $guzzleClient = new GuzzleClient(['timeout' => 60]);
            $goutteClient = new Client();
            $goutteClient->setClient($guzzleClient);
            self::$goutteClient = $goutteClient;
        }

        return self::$goutteClient;
    }
}
