<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;
use Yoti\Http\Curl\RequestHandler;

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
    private $headers;

    /**
     * @var \Yoti\Http\RequestHandlerInterface
     */
    private $handler;

    /**
     * Request constructor.
     *
     * @param string $method
     * @param string $url
     * @param Payload $payload
     * @param array $header
     *
     * @throws RequestException
     */
    public function __construct(
        $method,
        $url,
        Payload $payload = null,
        array $headers = []
    ) {
        $this->validateHttpMethod($method);
        $this->validateHeaders($headers);
        $this->method = $method;
        $this->url = $url;
        $this->payload = $payload;
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
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return Payload|null
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param \Yoti\Http\RequestHandlerInterface $handler
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function setHandler(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    private function getHandler()
    {
        if (is_null($this->handler)) {
            // Use Curl handler by default.
            $this->handler = new RequestHandler();
        }
        return $this->handler;
    }

    /**
     * Execute the request.
     *
     * @return \Yoti\Http\Response
     */
    public function execute()
    {
        return $this->getHandler()->execute($this);
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
    private function validateHttpMethod($httpMethod)
    {
        if (empty($httpMethod)) {
            throw new RequestException("HTTP Method must be specified");
        }
        if (!$this->methodIsAllowed($httpMethod)) {
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
    private function methodIsAllowed($httpMethod)
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
