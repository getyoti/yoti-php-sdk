<?php

namespace Yoti\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Exception\NetworkException;
use Yoti\Http\Exception\RequestException;

/**
 * Handle HTTP requests.
 */
class Client implements ClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @param array $config
     *   Configuration provided to \GuzzleHttp\Client::__construct
     */
    public function __construct(array $config = [])
    {
        $this->httpClient = new \GuzzleHttp\Client(array_merge(
            [
                RequestOptions::TIMEOUT => 30,
                RequestOptions::HTTP_ERRORS => false,
            ],
            $config
        ));
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->httpClient->send($request);
        } catch (ConnectException $e) {
            throw new NetworkException($e->getMessage(), $request);
        } catch (GuzzleException $e) {
            throw new RequestException($e->getMessage(), $request);
        }
    }
}
