<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;

class Request
{
    // HTTP methods
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $payload;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * @var array
     */
    private $headers;

    /**
     * AbstractRequestHandler constructor.
     *
     * @param string $apiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier - deprecated - use ::setSdkIdentifier() instead.
     *
     * @throws RequestException
     */
    public function __construct($method, $url, $queryParams = [], Payload $payload = null, $headers = [])
    {
        $this->validateHttpMethod($method);
        $this->validateHeaders($headers);
        $this->method = $method;
        $this->url = $url;
        $this->queryParams = $queryParams;
        $this->payload = (string) $payload;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set custom headers.
     *
     * @param string[] $headers
     *   Associative array of header names and values
     *
     * @throws RequestException
     */
    public function validateHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            if (!is_string($value)) {
                throw new RequestException("Header value for '{$name}' must be a string");
            }
        }
    }

    /**
     * Check if the provided HTTP method is valid.
     *
     * @param string $httpMethod
     *
     * @throws RequestException
     */
    private static function validateHttpMethod($httpMethod)
    {
        if (!self::methodIsAllowed($httpMethod)) {
            throw new RequestException("Unsupported HTTP Method {$httpMethod}", 400);
        }
    }

    /**
     * Check the HTTP method is allowed.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    private static function methodIsAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ];

        return in_array($httpMethod, $allowedMethods, true);
    }
}
