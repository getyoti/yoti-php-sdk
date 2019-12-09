<?php

namespace Yoti\Http;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Handle HTTP requests.
 */
class Client implements ClientInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     *   Configuration provided to \GuzzleHttp\Client::__construct
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(
            [
                RequestOptions::TIMEOUT => 30,
                RequestOptions::HTTP_ERRORS => false,
            ],
            $config
        );
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return (new \GuzzleHttp\Client($this->config))->send($request);
    }
}
