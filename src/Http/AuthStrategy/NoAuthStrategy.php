<?php

declare(strict_types=1);

namespace Yoti\Http\AuthStrategy;

use Yoti\Http\Payload;

/**
 * Authentication strategy that performs no authentication.
 *
 * Used for endpoints that do not require any authentication
 * (e.g., getSupportedDocuments in Java SDK).
 * Mirrors the Java SDK's NoAuthStrategy.
 */
class NoAuthStrategy implements AuthStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function createAuthHeaders(string $httpMethod, string $endpoint, ?Payload $payload = null): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryParams(): array
    {
        return [];
    }
}
