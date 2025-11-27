<?php

declare(strict_types=1);

namespace Yoti\Http\Auth;

use Yoti\Http\Payload;
use Yoti\Http\RequestSigner;
use Yoti\Util\PemFile;

/**
 * Signed request authentication strategy (existing mechanism).
 */
class SignedRequestAuthStrategy implements AuthStrategyInterface
{
    /** Digest HTTP header key. */
    private const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @param \Yoti\Util\PemFile $pemFile
     */
    public function __construct(PemFile $pemFile)
    {
        $this->pemFile = $pemFile;
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
        $headers[self::YOTI_DIGEST_HEADER_KEY] = RequestSigner::sign(
            $this->pemFile,
            $endpoint,
            $httpMethod,
            $payload
        );

        return $headers;
    }
}
