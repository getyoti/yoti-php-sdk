<?php

namespace YotiSandbox\Http;

use YotiSandbox\Exception\ResponseException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var string
     */
    private $token;

    /**
     * Response constructor.
     *
     * @param \Yoti\Http\Response $response
     *
     * @throws ResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseArr = $this->processData($response);
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
     * @param \Yoti\Http\Response $response
     *
     * @return mixed
     *
     * @throws ResponseException
     */
    private function processData(ResponseInterface $response)
    {
        $this->checkResponseStatus($response->getStatusCode());

        // Get decoded response data
        $responseJSON = $response->getBody();

        $responseArr = json_decode($responseJSON, true);
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
        if (json_last_error() !== JSON_ERROR_NONE) {
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
        if ($httpCode !== 201) {
            throw new ResponseException("Server responded with {$httpCode}", $httpCode);
        }
    }
}
