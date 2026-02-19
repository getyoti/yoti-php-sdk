<?php

declare(strict_types=1);

namespace Yoti\DocScan;

use Yoti\Constants;
use Yoti\Http\AuthStrategy\BearerTokenStrategy;
use Yoti\Util\Config;
use Yoti\Util\Env;
use Yoti\Util\PemFile;
use Yoti\Util\Validation;

/**
 * Fluent builder for constructing a {@see DocScanClient}.
 *
 * Supports two mutually exclusive authentication modes:
 *
 * 1. **Signed Request** (legacy): provide sdkId + PEM key pair
 *    via {@see withClientSdkId()} and {@see withPemFile()}/{@see withPemString()}/{@see withPemFilePath()}.
 *
 * 2. **Authentication Token** (central auth): provide a pre-obtained
 *    bearer token via {@see withAuthenticationToken()}.
 *
 * Usage:
 * ```php
 * // Signed request mode:
 * $client = DocScanClient::builder()
 *     ->withClientSdkId('your-sdk-id')
 *     ->withPemFilePath('/path/to/key.pem')
 *     ->build();
 *
 * // Authentication token mode:
 * $client = DocScanClient::builder()
 *     ->withAuthenticationToken('your-bearer-token')
 *     ->build();
 * ```
 */
class DocScanClientBuilder
{
    /**
     * @var string|null
     */
    private $authenticationToken;

    /**
     * @var string|null
     */
    private $sdkId;

    /**
     * @var PemFile|null
     */
    private $pemFile;

    /**
     * @var array<string, mixed>
     */
    private $options = [];

    /**
     * Set the authentication token for Bearer token auth mode.
     * Mutually exclusive with sdkId/PEM configuration.
     *
     * @param string $authenticationToken
     * @return $this
     */
    public function withAuthenticationToken(string $authenticationToken): self
    {
        $this->authenticationToken = $authenticationToken;
        return $this;
    }

    /**
     * Set the SDK client ID for signed request auth mode.
     *
     * @param string $sdkId
     * @return $this
     */
    public function withClientSdkId(string $sdkId): self
    {
        $this->sdkId = $sdkId;
        return $this;
    }

    /**
     * Set the PEM file for signed request auth mode.
     *
     * @param PemFile $pemFile
     * @return $this
     */
    public function withPemFile(PemFile $pemFile): self
    {
        $this->pemFile = $pemFile;
        return $this;
    }

    /**
     * Set the PEM from a file path for signed request auth mode.
     *
     * @param string $pemFilePath
     * @return $this
     */
    public function withPemFilePath(string $pemFilePath): self
    {
        $this->pemFile = PemFile::resolveFromString($pemFilePath);
        return $this;
    }

    /**
     * Set the PEM from a string for signed request auth mode.
     *
     * @param string $pemString
     * @return $this
     */
    public function withPemString(string $pemString): self
    {
        $this->pemFile = PemFile::resolveFromString($pemString);
        return $this;
    }

    /**
     * Set SDK configuration options.
     *
     * @param array<string, mixed> $options
     * @return $this
     */
    public function withOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Build the DocScanClient instance.
     *
     * @return DocScanClient
     * @throws \InvalidArgumentException if configuration is invalid
     */
    public function build(): DocScanClient
    {
        // Set API URL from environment variable.
        $this->options[Config::API_URL] = $this->options[Config::API_URL]
            ?? Env::get(Constants::ENV_DOC_SCAN_API_URL);

        $config = new Config($this->options);

        if ($this->authenticationToken !== null) {
            $this->validateAuthToken();
            $authStrategy = new BearerTokenStrategy($this->authenticationToken);
            $service = Service::withAuthStrategy($authStrategy, $config);
            return DocScanClient::fromService($service);
        }

        $this->validateForSignedRequest();
        $service = new Service($this->sdkId, $this->pemFile, $config);
        return DocScanClient::fromService($service);
    }

    /**
     * Validate that sdkId and PEM are provided for signed request mode.
     *
     * @throws \InvalidArgumentException
     */
    private function validateForSignedRequest(): void
    {
        if (empty($this->sdkId) || $this->pemFile === null) {
            throw new \InvalidArgumentException(
                'An sdkId and PEM file must be provided when not using an authentication token'
            );
        }
    }

    /**
     * Validate that sdkId and PEM are NOT provided when using auth token mode.
     *
     * @throws \InvalidArgumentException
     */
    private function validateAuthToken(): void
    {
        Validation::notEmptyString($this->authenticationToken, 'Authentication token');

        if ($this->sdkId !== null || $this->pemFile !== null) {
            throw new \InvalidArgumentException(
                'Must not supply sdkId or PEM file when using an authentication token'
            );
        }
    }
}
