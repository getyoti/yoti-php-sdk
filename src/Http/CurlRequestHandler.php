<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;
use Yoti\Http\Curl\RequestHandler;

/**
 * @deprecated 3.0.0 Replaced by \Yoti\Http\Curl\RequestHandler
 */
class CurlRequestHandler extends AbstractRequestHandler
{
    /**
     * Execute Request against the API.
     *
     * @param string $requestUrl
     * @param array $httpHeaders
     * @param string $httpMethod
     * @param Payload|NULL $payload
     * @param Request $request
     *
     * @return array
     *
     * @throws RequestException
     */
    protected function executeRequest(array $httpHeaders, $requestUrl, $httpMethod, $payload)
    {
        $requestHeaders = [];
        foreach ($httpHeaders as $httpHeader) {
            $headerParts = explode(':', $httpHeader);
            $name = array_shift($headerParts);
            $value = implode(':', $headerParts);
            $requestHeaders[$name] = $value;
        }

        $request = new Request(
            $httpMethod,
            $requestUrl,
            $payload,
            $requestHeaders
        );

        $response = (new RequestHandler())
          ->execute($request);

        return [
            'response' => $response->getBody(),
            'http_code' => $response->getStatusCode()
        ];
    }
}
