<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;

class CurlRequestHandler extends AbstractRequestHandler
{
    /**
     * Execute Request against the API.
     *
     * @param string $requestUrl
     * @param array $httpHeaders
     * @param string $httpMethod
     * @param Payload|NULL $payload
     *
     * @return array
     *
     * @throws RequestException
     */
    protected function executeRequest(array $httpHeaders, $requestUrl, $httpMethod, $payload)
    {
        return $this->execute();
    }

    /**
     * Execute Request against the API.
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws RequestException
     */
    protected function execute(Request $request)
    {
        $result = [];

        $ch = curl_init($request->getUrl());
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $request->getHeaders(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());

        // Only send payload data for methods that need it.
        if (!empty($request->getPayload())) {
            // Send payload data as a JSON string
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPayload());
        }

        // Set response data
        $result['response'] = curl_exec($ch);
        // Set response code
        $result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check if any related Curl error occurred.
        if (curl_error($ch)) {
            throw new RequestException(curl_error($ch));
        }

        // Close the session
        curl_close($ch);

        return $result;
    }
}
