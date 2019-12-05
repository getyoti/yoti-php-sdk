<?php

namespace Yoti\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Handle HTTP requests.
 */
class Client implements ClientInterface
{
    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return (new \GuzzleHttp\Client())->send($request);
    }
}
