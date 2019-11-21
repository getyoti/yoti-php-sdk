<?php

namespace Yoti\Http\Curl;

use Yoti\Http\RequestHandlerInterface;
use Yoti\Http\Request;
use Yoti\Http\Response;
use Yoti\Exception\RequestException;

/**
 * Handle HTTP requests.
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * Curl options.
     *
     * @var array
     */
    private $options = [];

    /**
     * Execute HTTP request.
     *
     * @param Request $request
     *
     * @return \Yoti\Http\Response
     */
    public function execute(Request $request)
    {
        $headers = [];
        if ($request->getHeaders()) {
            foreach ($request->getHeaders() as $name => $value) {
                $headers[] = "{$name}: {$value}";
            }
        }

        $ch = curl_init($request->getUrl());

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());

        // Set additional options.
        foreach ($this->options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        // Only send payload data for methods that need it.
        if ($request->getPayload()) {
            // Send payload data as a JSON string
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPayload()->getPayloadJSON());
        }

        // Get response headers.
        $responseHeaders = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) use (&$responseHeaders) {
            // Handle multi-line headers - see RFC2616 section 4.
            if ($header[0] == ' ' || $header[0] == "\t") {
                $responseHeaders[] = array_pop($responseHeaders) . ' ' . trim($header);
            } else {
                $responseHeaders[] = trim($header);
            }
            return strlen($header);
        });

        // Get response data.
        $response = curl_exec($ch);

        // Get response code.
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check if any related Curl error occurred.
        if ($response === false) {
            $error = curl_error($ch);
        }

        // Close the session.
        curl_close($ch);

        // Throw if there was an error.
        if (!empty($error)) {
            throw new RequestException($error);
        }

        return new Response($response, $statusCode, $this->createResponseHeadersMap($responseHeaders));
    }

    /**
     * Create headers map from array of headers.
     *
     * @param string[]
     *
     * @return string[]
     */
    private function createResponseHeadersMap($headers)
    {
        $headersMap = [];

        foreach ($headers as $header) {
            $parts = array_map('trim', explode(':', $header));
            if (count($parts) === 2) {
                list($key, $value) = $parts;
                $headersMap[$key] = $value;
            }
        }

        return $headersMap;
    }

    /**
     * Set additional options.
     *
     * @see https://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param int $key
     * @param mixed $value
     *
     * @return Yoti\Http\Curl\RequestHandler
     *   This request handler
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }
}
