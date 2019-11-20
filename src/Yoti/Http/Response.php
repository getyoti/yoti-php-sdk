<?php

namespace Yoti\Http;

use Yoti\Util\Validation;

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
     * @var string[]
     */
    private $headers;

    /**
     * @param string $body
     * @param int $statusCode
     */
    public function __construct(
        $body,
        $statusCode,
        array $headers = []
    ) {
        $this->body = (string) $body;
        $this->statusCode = (int) $statusCode;

        Validation::isArrayOfStrings($headers, 'headers');
        $this->headers = $headers;
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

    /**
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
