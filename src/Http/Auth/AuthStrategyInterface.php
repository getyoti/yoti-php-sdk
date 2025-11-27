<?php

declare(strict_types=1);

namespace Yoti\Http\Auth;

use Yoti\Http\Payload;

/**
 * Interface for authentication strategies.
 */
interface AuthStrategyInterface
{
    /**
     * Apply authentication to the request headers.
     *
     * @param array<string, string> $headers
     *   The request headers to modify.
     * @param string $endpoint
     *   The request endpoint with query parameters.
     * @param string $httpMethod
     *   The HTTP method (GET, POST, etc.).
     * @param \Yoti\Http\Payload|null $payload
     *   The request payload, if any.
     *
     * @return array<string, string>
     *   The modified headers with authentication applied.
     */
    public function applyAuth(
        array $headers,
        string $endpoint,
        string $httpMethod,
        ?Payload $payload = null
    ): array;
}
