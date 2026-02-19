<?php

declare(strict_types=1);

namespace Yoti\Http;

use GuzzleHttp\Psr7\Request as RequestMessage;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamInterface;
use Yoti\Http\AuthStrategy\AuthStrategyInterface;
use Yoti\Http\AuthStrategy\SignedRequestStrategy;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

class RequestBuilder
{
    /** Digest HTTP header key. */
    private const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';

    /** SDK Identifier HTTP header key. */
    private const YOTI_SDK_IDENTIFIER_HEADER_KEY = 'X-Yoti-SDK';

    /** SDK Version HTTP header key. */
    private const YOTI_SDK_VERSION_HEADER_KEY = 'X-Yoti-SDK-Version';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var AuthStrategyInterface|null
     */
    private $authStrategy;

    /**
     * @var array<string, string>
     */
    private $headers = [];

    /**
     * @var array<string, string>
     */
    private $queryParams = [];

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var \Yoti\Http\Payload
     */
    private $payload;

    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $client;

    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @var MultipartEntity
     */
    private $multipartEntity;

    /**
     * @param \Yoti\Util\Config|null $config
     */
    public function __construct(?Config $config = null)
    {
        $this->config = $config ?? new Config();
    }

    /**
     * @param string $baseUrl
     *   Base URL with no trailing slashes.
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * @param string $endpoint
     *   Endpoint with a single leading slash.
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withEndpoint(string $endpoint): self
    {
        $this->endpoint = '/' . ltrim($endpoint, '/');
        return $this;
    }

    /**
     * @param \Yoti\Util\PemFile $pemFile
     *
     * @return RequestBuilder
     */
    public function withPemFile(PemFile $pemFile): self
    {
        $this->pemFile = $pemFile;
        return $this;
    }

    /**
     * @param string $filePath
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPemFilePath(string $filePath): self
    {
        return $this->withPemFile(PemFile::fromFilePath($filePath));
    }

    /**
     * @param string $content
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPemString(string $content): self
    {
        return $this->withPemFile(PemFile::fromString($content));
    }

    /**
     * Set the authentication strategy for this request.
     *
     * When set, the auth strategy will be used instead of the default
     * signed request behavior. If neither authStrategy nor pemFile is set,
     * build() will throw an exception.
     *
     * @param AuthStrategyInterface $authStrategy
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withAuthStrategy(AuthStrategyInterface $authStrategy): self
    {
        $this->authStrategy = $authStrategy;
        return $this;
    }

    /**
     * @param string $method
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    public function withGet(): self
    {
        return $this->withMethod(Request::METHOD_GET);
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPost(): self
    {
        return $this->withMethod(Request::METHOD_POST);
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPut(): self
    {
        return $this->withMethod(Request::METHOD_PUT);
    }

    /**
     * @param \Yoti\Http\Payload $payload
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPayload(Payload $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param \Psr\Http\Client\ClientInterface $client
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withClient(ClientInterface $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withQueryParam(string $name, string $value): self
    {
        $this->queryParams[$name] = $value;
        return $this;
    }

    /**
     * @return void
     */
    private function forMultipartRequest(): void
    {
        if ($this->multipartEntity == null) {
            $this->multipartEntity = MultipartEntity::create();
        }
    }

    /**
     * Sets the boundary to be used on the multipart request
     *
     * @param string $multipartBoundary
     * @return RequestBuilder
     */
    public function withMultipartBoundary(string $multipartBoundary): RequestBuilder
    {
        $this->forMultipartRequest();
        $this->multipartEntity->setBoundary($multipartBoundary);

        return $this;
    }

    /**
     * Adds a binary body to the multipart request.
     *
     * Note: the Signed Request must be specified with a boundary also
     * in order to make use of the Multipart request
     *
     * @param string $name
     * @param string $payload
     * @param string $contentType
     * @param string $fileName
     * @return $this
     */
    public function withMultipartBinaryBody(
        string $name,
        string $payload,
        string $contentType,
        string $fileName
    ): RequestBuilder {
        $this->multipartEntity->addBinaryBody($name, $payload, $contentType, $fileName);

        return $this;
    }

    /**
     * Return the request headers including defaults.
     *
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        $sdkIdentifier = $this->config->getSdkIdentifier();
        $sdkVersion = $this->config->getSdkVersion();

        // Prepare request Http Headers
        $defaultHeaders = [
            self::YOTI_SDK_IDENTIFIER_HEADER_KEY => $sdkIdentifier,
            self::YOTI_SDK_VERSION_HEADER_KEY => "{$sdkIdentifier}-{$sdkVersion}",
            'Accept' => 'application/json',
        ];

        if (isset($this->payload)) {
            $defaultHeaders['Content-Type'] = 'application/json';
        }

        return array_merge($defaultHeaders, $this->headers);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateMethod(): void
    {
        if (!isset($this->method)) {
            throw new \InvalidArgumentException('HTTP Method must be specified');
        }

        if (
            !in_array(
                $this->method,
                [
                    Request::METHOD_GET,
                    Request::METHOD_POST,
                    Request::METHOD_PUT,
                    Request::METHOD_PATCH,
                    Request::METHOD_DELETE,
                ],
                true
            )
        ) {
            throw new \InvalidArgumentException("Unsupported HTTP Method {$this->method}");
        }
    }

    /**
     * @return \Yoti\Http\Request
     *
     * @throws \InvalidArgumentException
     */
    public function build(): Request
    {
        if (!isset($this->baseUrl)) {
            throw new \InvalidArgumentException('Base URL must be provided to ' . __CLASS__);
        }

        $this->validateMethod();

        // Resolve the auth strategy:
        // 1. Explicit authStrategy takes priority
        // 2. PemFile present: use legacy SignedRequestStrategy (backward compatible)
        // 3. Neither: throw
        $authStrategy = $this->resolveAuthStrategy();

        // Merge strategy query params with manually set query params.
        // Manual params go first to preserve backward-compatible URL ordering.
        $strategyQueryParams = $authStrategy->createQueryParams();
        $allQueryParams = array_merge($this->queryParams, $strategyQueryParams);

        $endpointWithParams = $this->endpoint . '?' . http_build_query($allQueryParams);

        $payload = isset($this->multipartEntity) ? Payload::fromStream($this->multipartEntity->createStream()) :
            $this->payload;

        // Get auth headers from strategy.
        $authHeaders = $authStrategy->createAuthHeaders(
            $this->method,
            $endpointWithParams,
            $payload
        );

        // Merge auth headers into manual headers.
        foreach ($authHeaders as $name => $value) {
            $this->withHeader($name, $value);
        }

        $url = $this->baseUrl . $endpointWithParams;

        $message = new RequestMessage(
            $this->method,
            Utils::uriFor($url),
            $this->getHeaders(),
            $this->getBodyByTypeOfRequest()
        );

        return new Request($message, $this->client ?? $this->config->getHttpClient());
    }

    /**
     * Resolve the authentication strategy to use.
     *
     * @return AuthStrategyInterface
     *
     * @throws \InvalidArgumentException
     */
    private function resolveAuthStrategy(): AuthStrategyInterface
    {
        if (isset($this->authStrategy)) {
            return $this->authStrategy;
        }

        if (isset($this->pemFile)) {
            return new SignedRequestStrategy($this->pemFile);
        }

        throw new \InvalidArgumentException(
            'Either an AuthStrategy or a PEM file must be provided to ' . __CLASS__
        );
    }

    /**
     * @return StreamInterface|null
     */
    private function getBodyByTypeOfRequest(): ?StreamInterface
    {
        if (isset($this->payload)) {
            return $this->payload->toStream();
        } elseif (isset($this->multipartEntity)) {
            return $this->multipartEntity->createStream();
        } else {
            return null;
        }
    }
}
