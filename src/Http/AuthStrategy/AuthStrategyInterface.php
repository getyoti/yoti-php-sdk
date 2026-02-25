<?php

declare(strict_types=1);

namespace Yoti\Http\AuthStrategy;

use Yoti\Http\Payload;

/**
 * Interface for authentication strategies used when building HTTP requests.
 *
 * Implementations define how auth headers and query parameters are generated.
 * This mirrors the Java SDK's AuthStrategy interface.
 */
interface AuthStrategyInterface
{
    /**
     * Create authentication headers for the request.
     *
     * @param string $httpMethod The HTTP method (GET, POST, PUT, etc.)
     * @param string $endpoint The request endpoint (path + query string)
     * @param Payload|null $payload The request payload, if any
     *
     * @return array<string, string> Headers to include in the request
     */
    public function createAuthHeaders(string $httpMethod, string $endpoint, ?Payload $payload = null): array;

    /**
     * Create query parameters required by this auth strategy.
     *
     * @return array<string, string> Query parameters to include in the request
     */
    public function createQueryParams(): array;
}
