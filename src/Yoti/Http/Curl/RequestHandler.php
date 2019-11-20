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
     * Response headers.
     *
     * @var array
     */
    private $responseHeaders = [];

    /**
     * Current resourceID.
     *
     * Will increment per request.
     *
     * @var int
     */
    private $resourceCount = 0;

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
            CURLOPT_HEADERFUNCTION => [$this, 'setResponseHeader'],
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());

        // Set additional options.
        foreach ($this->options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        // Set a resource ID unique to this request handler instance.
        $this->setResourceId($ch);

        // Only send payload data for methods that need it.
        if ($request->getPayload()) {
            // Send payload data as a JSON string
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPayload()->getPayloadJSON());
        }

        // Get response data.
        $response = curl_exec($ch);

        // Get response code.
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Get the response headers.
        $responseHeadersMap = $this->getResponseHeadersMap($ch);
        $this->unsetResponseHeaders($ch);

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

        return new Response($response, $statusCode, $responseHeadersMap);
    }

    /**
     * Get response headers as a map.
     *
     * @return string[]
     */
    private function getResponseHeadersMap($ch)
    {
        $headersMap = [];

        foreach ($this->getResponseHeaders($ch) as $header) {
            $parts = array_map('trim', explode(':', $header));
            if (count($parts) === 2) {
                list($key, $value) = $parts;
                $headersMap[$key] = $value;
            }
        }

        return $headersMap;
    }

    /**
     * Unset response headers for provided resource.
     *
     * @param resource $ch
     */
    private function unsetResponseHeaders($ch)
    {
        $resourceId = $this->getResourceId($ch);
        unset($this->responseHeaders[$resourceId]);
    }

    /**
     * Get response headers.
     *
     * @param resource $ch
     *
     * @return string[]
     */
    private function getResponseHeaders($ch)
    {
        $resourceId = $this->getResourceId($ch);

        if (isset($this->responseHeaders[$resourceId])) {
            return $this->responseHeaders[$resourceId];
        }

        return [];
    }

    /**
     * Callback to set response headers.
     *
     * @param resource $ch
     *   cURL handler.
     * @param string $header
     *   Response header.
     *
     * @return int
     */
    private function setResponseHeader($ch, $header)
    {
        $chKey = $this->getResourceId($ch);

        // Handle multi-line headers - see RFC2616 section 4.
        if (isset($this->responseHeaders[$chKey]) && ($header[0] == ' ' || $header[0] == "\t")) {
            $this->responseHeaders[$chKey][] = array_pop($this->responseHeaders[$chKey]) . ' ' . trim($header);
        } else {
            $this->responseHeaders[$chKey][] = trim($header);
        }

        return strlen($header);
    }

    /**
     * Set a resource ID unique to this request handler instance.
     *
     * @param resource $ch
     *
     * @return int
     */
    private function setResourceId($ch)
    {
        $resourceId = $this->resourceCount++;
        curl_setopt($ch, CURLOPT_PRIVATE, $resourceId);
        return $resourceId;
    }

    /**
     * Get resource ID unique to this request handler instance.
     *
     * @param resource $ch
     *
     * @return int
     */
    private function getResourceId($ch)
    {
        return curl_getinfo($ch, CURLOPT_PRIVATE);
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
