<?php

namespace Yoti\Http;

class Request
{
    /**
     * @var \Yoti\Http\AbstractRequestHandler
     */
    private $requestHandler;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * @var Payload
     */
    private $payload;

    /**
     * @param \Yoti\Http\AbstractRequestHandler $requestHandler
     * @param string $path
     * @param array $queryParams
     * @param Payload $payload
     */
    public function __construct($requestHandler, $path, $queryParams = [], Payload $payload = null)
    {
        $this->requestHandler = $requestHandler;
        $this->path = $path;
        $this->queryParams = $queryParams;
        $this->payload = $payload;
    }

    /**
     * @param string $httpMethod
     *
     * @return array
     */
    public function sendRequest($httpMethod)
    {
        return $this->requestHandler->sendRequest(
            $this->path,
            $httpMethod,
            $this->payload,
            $this->queryParams
        );
    }

    /**
     * Performs GET request.
     *
     * @return array
     */
    public function get()
    {
        return $this->sendRequest(AbstractRequestHandler::METHOD_GET);
    }

    /**
     * Performs POST request.
     *
     * @return array
     */
    public function post()
    {
        $this->sendRequest(AbstractRequestHandler::METHOD_POST);
    }
}
