<?php

namespace Yoti\Http;

use Psr\Http\Message\RequestInterface;
use Yoti\Exception\RequestException;

class Request
{
    /** HTTP methods */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    private $message;

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $client;

    /**
     * Request constructor.
     *
     * @param \Psr\Http\Message\RequestInterface $message
     *
     * @throws RequestException
     */
    public function __construct(RequestInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @param \Psr\Http\Client\ClientInterface $client
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function setClient(\Psr\Http\Client\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Psr\Http\Client\ClientInterface
     */
    private function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client();
        }
        return $this->client;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Execute the request.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute()
    {
        return $this->getClient()->sendRequest($this->getMessage());
    }
}
