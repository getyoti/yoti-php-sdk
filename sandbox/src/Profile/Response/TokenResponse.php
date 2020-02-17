<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Response;

use Psr\Http\Message\ResponseInterface;
use Yoti\Sandbox\Exception\ResponseException;
use Yoti\Util\Json;

class TokenResponse
{
    /**
     * @var string
     */
    private $token;

    /**
     * Response constructor.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \Yoti\Sandbox\Exception\ResponseException
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
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array<string, mixed>
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
     * @param int $httpCode
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
