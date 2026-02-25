<?php

declare(strict_types=1);

namespace Yoti\Auth;

use Psr\Http\Client\ClientInterface;
use Yoti\Util\Env;
use Yoti\Util\PemFile;

/**
 * Builder for AuthenticationTokenGenerator.
 *
 * Provides a fluent API for configuring and creating an
 * AuthenticationTokenGenerator instance.
 *
 * Mirrors the Java SDK's AuthenticationTokenGenerator.Builder.
 */
class Builder
{
    /**
     * @var string|null
     */
    private $sdkId;

    /**
     * @var PemFile|null
     */
    private $pemFile;

    /**
     * @var callable|null
     */
    private $jwtIdSupplier;

    /**
     * @var string|null
     */
    private $authApiUrl;

    /**
     * @var ClientInterface|null
     */
    private $httpClient;

    /**
     * Sets the SDK ID that the authorization token will be generated against.
     *
     * @param string $sdkId
     *
     * @return self
     */
    public function withSdkId(string $sdkId): self
    {
        $this->sdkId = $sdkId;
        return $this;
    }

    /**
     * Sets the PEM file used for signing the JWT.
     *
     * @param PemFile $pemFile
     *
     * @return self
     */
    public function withPemFile(PemFile $pemFile): self
    {
        $this->pemFile = $pemFile;
        return $this;
    }

    /**
     * Sets the PEM file from a file path.
     *
     * @param string $filePath
     *
     * @return self
     */
    public function withPemFilePath(string $filePath): self
    {
        return $this->withPemFile(PemFile::fromFilePath($filePath));
    }

    /**
     * Sets the PEM file from a string.
     *
     * @param string $content
     *
     * @return self
     */
    public function withPemString(string $content): self
    {
        return $this->withPemFile(PemFile::fromString($content));
    }

    /**
     * Sets a callable that generates unique JWT IDs.
     * Defaults to generating UUID v4 if not provided.
     *
     * @param callable $jwtIdSupplier A callable that returns a string
     *
     * @return self
     */
    public function withJwtIdSupplier(callable $jwtIdSupplier): self
    {
        $this->jwtIdSupplier = $jwtIdSupplier;
        return $this;
    }

    /**
     * Sets a custom auth API URL (primarily for testing).
     *
     * @param string $authApiUrl
     *
     * @return self
     */
    public function withAuthApiUrl(string $authApiUrl): self
    {
        $this->authApiUrl = $authApiUrl;
        return $this;
    }

    /**
     * Sets a custom PSR-18 HTTP client (primarily for testing).
     *
     * @param ClientInterface $httpClient
     *
     * @return self
     */
    public function withHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Builds the AuthenticationTokenGenerator.
     *
     * @return AuthenticationTokenGenerator
     *
     * @throws \InvalidArgumentException
     */
    public function build(): AuthenticationTokenGenerator
    {
        if ($this->sdkId === null || $this->sdkId === '') {
            throw new \InvalidArgumentException("'sdkId' must not be empty or null");
        }

        if ($this->pemFile === null) {
            throw new \InvalidArgumentException("'pemFile' must not be null");
        }

        $jwtIdSupplier = $this->jwtIdSupplier ?? static function (): string {
            return self::generateUuidV4();
        };

        // Resolve auth URL: custom > environment variable > default
        $authApiUrl = $this->authApiUrl
            ?? Env::get(Properties::ENV_YOTI_AUTH_URL)
            ?? Properties::DEFAULT_YOTI_AUTH_URL;

        return new AuthenticationTokenGenerator(
            $this->sdkId,
            $this->pemFile,
            $jwtIdSupplier,
            $authApiUrl,
            $this->httpClient
        );
    }

    /**
     * Generate a UUID v4.
     *
     * @return string
     */
    private static function generateUuidV4(): string
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
