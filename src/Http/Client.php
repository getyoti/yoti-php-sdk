<?php

declare(strict_types=1);

namespace Yoti\Http;

use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Exception\ClientException;
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
     * @param array<string, mixed> $config
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
        } catch (GuzzleConnectException $e) {
            throw new NetworkException($e->getMessage(), $request, $e);
        } catch (GuzzleRequestException $e) {
            throw new RequestException($e->getMessage(), $request, $e);
        } catch (GuzzleException $e) {
            throw new ClientException($e->getMessage(), 0, $e);
        }
    }
}
