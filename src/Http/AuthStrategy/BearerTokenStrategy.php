<?php

declare(strict_types=1);

namespace Yoti\Http\AuthStrategy;

use Yoti\Http\Payload;

/**
 * Authentication strategy that uses a Bearer token for authorization.
 *
 * Sets the Authorization header with a Bearer token obtained from
 * the Yoti authentication service (OAuth2 client_credentials flow).
 * Mirrors the Java SDK's AuthTokenStrategy.
 */
class BearerTokenStrategy implements AuthStrategyInterface
{
    /**
     * @var string
     */
    private $authenticationToken;

    /**
     * @param string $authenticationToken The Bearer token
     */
    public function __construct(string $authenticationToken)
    {
        if ($authenticationToken === '') {
            throw new \InvalidArgumentException('Authentication token must not be empty');
        }
        $this->authenticationToken = $authenticationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function createAuthHeaders(string $httpMethod, string $endpoint, ?Payload $payload = null): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->authenticationToken,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryParams(): array
    {
        return [];
    }
}
