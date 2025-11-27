<?php

declare(strict_types=1);

namespace Yoti\Http\Auth;

use Yoti\Http\Payload;

/**
 * Token-based authentication strategy (Central Auth).
 */
class TokenAuthStrategy implements AuthStrategyInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     *   The authentication token.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function applyAuth(
        array $headers,
        string $endpoint,
        string $httpMethod,
        ?Payload $payload = null
    ): array {
        $headers['Authorization'] = 'Bearer ' . $this->token;

        return $headers;
    }
}
