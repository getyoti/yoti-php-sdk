<?php

declare(strict_types=1);

namespace YotiSandbox\Http;

use Psr\Http\Message\ResponseInterface;
use Yoti\Util\Json;
use YotiSandbox\Exception\ResponseException;

class TokenResponse
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
    public function getToken(): string
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
    private function processData(ResponseInterface $response): array
    {
        $this->checkResponseStatus($response->getStatusCode());

        // Get decoded response data.
        $responseArr = Json::decode((string) $response->getBody(), true);

        if (!isset($responseArr['token'])) {
            throw new ResponseException('Token key is missing', 404);
        }

        return $responseArr;
    }

    /**
     * @param string $httpCode
     *
     * @throws ResponseException
     */
    private function checkResponseStatus(int $httpCode): void
    {
        if ($httpCode !== 201) {
            throw new ResponseException("Server responded with {$httpCode}", $httpCode);
        }
    }
}
