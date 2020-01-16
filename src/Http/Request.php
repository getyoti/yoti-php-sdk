<?php

declare(strict_types=1);

namespace Yoti\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
     * @param \Psr\Http\Client\ClientInterface $client
     *
     * @throws RequestException
     */
    public function __construct(RequestInterface $message, ClientInterface $client)
    {
        $this->message = $message;
        $this->client = $client;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getMessage(): RequestInterface
    {
        return $this->message;
    }

    /**
     * Execute the request.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function execute(): ResponseInterface
    {
        return $this->client->sendRequest($this->getMessage());
    }
}
