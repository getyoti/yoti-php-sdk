<?php

namespace Yoti\Http;

class Response
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @param string $body
     * @param int $statusCode
     */
    public function __construct(
        $body,
        $statusCode
    ) {
        $this->body = (string) $body;
        $this->statusCode = (int) $statusCode;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
