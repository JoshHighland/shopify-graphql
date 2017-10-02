<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 02/10/2017
 * Time: 16:00
 */

namespace Shopify;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;

class Shop
{

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param $key
     *
     * @return Shop
     */
    public function setKey($key): Shop
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param $domain
     *
     * @return Shop
     */
    public function setDomain($domain): Shop
    {
        if (!preg_match('/^https:\/\//i', $domain)) {
            $domain = sprintf("https://%s.myshopify.com/api/graphql", $domain);
        }
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     *
     * @return Shop
     */
    public function setClient(ClientInterface $client): Shop
    {
        $this->client = $client;
        return $this;
    }

    public function getRequestBody($graphQL)
    {
        return [
            'headers' => [
                'Content-Type' => 'application/graphql',
                'X-Shopify-Storefront-Access-Token' => $this->getKey()
            ],
            'body' => $graphQL
        ];
    }

    /**
     * Shop constructor.
     *
     * @param $domain
     * @param $key
     */
    public function __construct($domain, $key, ClientInterface $client = null)
    {
        $this->setKey($key)
            ->setDomain($domain)
            ->setClient($client);
    }

    public function query($closure, $raw = false)
    {

        /** @var Response $response */
        $response = $this
            ->client
            ->get($this->getDomain(), $this->getRequestBody($closure()));

        if ($raw) {
            return $response;
        }

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    public function push()
    {

    }
}