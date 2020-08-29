<?php

declare(strict_types=1);

namespace Yoti\Http;

use GuzzleHttp\Psr7\Request as RequestMessage;
use Psr\Http\Client\ClientInterface;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

use function GuzzleHttp\Psr7\uri_for;

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
     * @param \Yoti\Util\Config $config
     */
    public function __construct(Config $config = null)
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
     * @return string
     */
    private static function generateNonce(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
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

        if (!isset($this->pemFile)) {
            throw new \InvalidArgumentException('Pem file must be provided to ' . __CLASS__);
        }

        $this->validateMethod();

        // Add nonce and timestamp to the URL.
        $this
            ->withQueryParam('nonce', self::generateNonce())
            ->withQueryParam('timestamp', (string) (round(microtime(true) * 1000)));

        $endpointWithParams = $this->endpoint . '?' . http_build_query($this->queryParams);

        $this->withHeader(self::YOTI_DIGEST_HEADER_KEY, RequestSigner::sign(
            $this->pemFile,
            $endpointWithParams,
            $this->method,
            $this->payload
        ));

        $url = $this->baseUrl . $endpointWithParams;

        $message = new RequestMessage(
            $this->method,
            uri_for($url),
            $this->getHeaders(),
            isset($this->payload) ? $this->payload->toStream() : null
        );

        return new Request($message, $this->client ?? $this->config->getHttpClient());
    }
}
