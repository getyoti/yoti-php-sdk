<?php

declare(strict_types=1);

namespace Yoti\Http\AuthStrategy;

use Yoti\Http\Payload;
use Yoti\Http\RequestSigner;
use Yoti\Util\PemFile;

/**
 * Authentication strategy that signs requests using the Yoti digest mechanism.
 *
 * This generates nonce + timestamp query params and an X-Yoti-Auth-Digest header,
 * matching the existing signed request behavior in the PHP SDK.
 * Mirrors the Java SDK's DocsSignedRequestStrategy / SignedRequestStrategy.
 */
class SignedRequestStrategy implements AuthStrategyInterface
{
    /**
     * @var PemFile
     */
    private $pemFile;

    /**
     * @var string|null
     */
    private $sdkId;

    /**
     * @param PemFile $pemFile The PEM file used for signing
     * @param string|null $sdkId Optional SDK ID to include as query param
     */
    public function __construct(PemFile $pemFile, ?string $sdkId = null)
    {
        $this->pemFile = $pemFile;
        $this->sdkId = $sdkId;
    }

    /**
     * {@inheritdoc}
     */
    public function createAuthHeaders(string $httpMethod, string $endpoint, ?Payload $payload = null): array
    {
        $digest = RequestSigner::sign(
            $this->pemFile,
            $endpoint,
            $httpMethod,
            $payload
        );

        return [
            'X-Yoti-Auth-Digest' => $digest,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryParams(): array
    {
        $params = [
            'nonce' => self::generateNonce(),
            'timestamp' => (string)(round(microtime(true) * 1000)),
        ];

        if ($this->sdkId !== null) {
            $params['sdkId'] = $this->sdkId;
        }

        return $params;
    }

    /**
     * Generate a UUID v4 nonce.
     *
     * @return string
     */
    private static function generateNonce(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
