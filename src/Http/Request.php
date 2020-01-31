<?php

declare(strict_types=1);

namespace Yoti\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Request
{
    /** HTTP methods */
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

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
