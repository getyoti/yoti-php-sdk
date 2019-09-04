<?php

namespace Yoti\Http;

class Response
{
    /**
     * @param string $response
     * @param int $statusCode
     */
    public function __construct(
        $response,
        $statusCode
    ) {
        $this->response = (string) $response;
        $this->statusCode = (int) $statusCode;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
