<?php

namespace YotiSandbox\Http;

use YotiSandbox\Exception\ResponseException;

class Response
{
    /**
     * @var string
     */
    private $token;

    /**
     * Response constructor.
     *
     * @param array $result
     *
     * @throws ResponseException
     */
    public function __construct(array $result)
    {
        $responseArr = $this->processData($result);
        $this->token = $responseArr['token'];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param array $result
     *
     * @return mixed
     *
     * @throws ResponseException
     */
    private function processData(array $result)
    {
        $this->checkResponseStatus($result['http_code']);

        // Get decoded response data
        $responseArr = $result['response'];

        $this->checkJsonError();

        if (!isset($responseArr['token'])) {
            throw new ResponseException('Token key is missing', 404);
        }

        return $responseArr;
    }

    /**
     * Check if any error occurred during JSON decode.
     *
     * @throws ResponseException
     */
    private function checkJsonError()
    {
        if(json_last_error() !== JSON_ERROR_NONE)
        {
            throw new ResponseException('JSON response was invalid', 502);
        }
    }

    /**
     * @param string $httpCode
     *
     * @throws ResponseException
     */
    private function checkResponseStatus($httpCode)
    {
        $httpCode = (int) $httpCode;
        if ($httpCode !== 201)
        {
            throw new ResponseException("Server responded with {$httpCode}", $httpCode);
        }
    }
}