<?php

namespace Yoti\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;
use Yoti\Http\Exception\ClientException;
use Yoti\Http\Exception\NetworkException;
use Yoti\Http\Exception\RequestException;

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
        try {
            return (new \GuzzleHttp\Client($this->config))->send($request);
        } catch (ConnectException $e) {
            throw new NetworkException($e->getMessage(), $request);
        } catch (\RuntimeException $e) {
            throw new RequestException($e->getMessage(), $request);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage());
        }
    }
}
