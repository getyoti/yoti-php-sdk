<?php

namespace Yoti\Http;

use GuzzleHttp\Psr7\Request as RequestMessage;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface;
use Yoti\Util\Constants;
use Yoti\Util\PemFile;
use Yoti\Util\Validation;

class RequestBuilder
{
    /**
     * Accepted HTTP header values for X-Yoti-SDK-Integration header.
     *
     * @var array
     */
    private $acceptedsdkIdentifiers = [
        'PHP',
        'WordPress',
        'Drupal',
        'Joomla',
    ];

    /** Digest HTTP header key. */
    const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';

    /** SDK Identifier HTTP header key. */
    const YOTI_SDK_IDENTIFIER_KEY = 'X-Yoti-SDK';

    /** SDK Version HTTP header key. */
    const YOTI_SDK_VERSION = 'X-Yoti-SDK-Version';

    /** Default SDK Identifier. */
    const YOTI_SDK_IDENTIFIER = 'PHP';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var string
     */
    private $sdkIdentifier = self::YOTI_SDK_IDENTIFIER;

    /**
     * @var string
     */
    private $sdkVersion = null;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
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
     * @param string $baseUrl
     *   Base URL with no trailing slashes.
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withBaseUrl($baseUrl)
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
    public function withEndpoint($endpoint)
    {
        $this->endpoint = '/' . ltrim($endpoint, '/');
        return $this;
    }

    /**
     * @param \Yoti\Util\PemFile $pemFile
     *
     * @return RequestBuilder
     */
    private function withPemFile(PemFile $pemFile)
    {
        $this->pemFile = $pemFile;
        return $this;
    }

    /**
     * @param string $filePath
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPemFilePath($filePath)
    {
        return $this->withPemFile(PemFile::fromFilePath($filePath));
    }

    /**
     * @param string $content
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPemString($content)
    {
        return $this->withPemFile(PemFile::fromString($content));
    }

    /**
     * @param string $method
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    public function withGet()
    {
        return $this->withMethod(Request::METHOD_GET);
    }

    /**
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPost()
    {
        return $this->withMethod(Request::METHOD_POST);
    }

    /**
     * @param \Yoti\Http\Payload $payload
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPayload(Payload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param \Psr\Http\Client\ClientInterface $client
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $sdkIdentifier
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withSdkIdentifier($sdkIdentifier)
    {
        if (!in_array($sdkIdentifier, $this->acceptedsdkIdentifiers, true)) {
            throw new \InvalidArgumentException(sprintf(
                "'%s' is not in the list of accepted identifiers: %s",
                $sdkIdentifier,
                implode(', ', $this->acceptedsdkIdentifiers)
            ));
        }
        $this->sdkIdentifier = $sdkIdentifier;
        return $this;
    }

    /**
     * @param string $sdkVersion
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withSdkVersion($sdkVersion)
    {
        Validation::isString($sdkVersion, 'SDK version');
        $this->sdkVersion = $sdkVersion;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withHeader($name, $value)
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
    public function withQueryParam($name, $value)
    {
        $this->queryParams[$name] = $value;
        return $this;
    }

    /**
     * Return the request headers including defaults.
     *
     * @return array
     */
    private function getHeaders()
    {
        // Prepare request Http Headers
        $defaultHeaders = [
            self::YOTI_SDK_IDENTIFIER_KEY => $this->sdkIdentifier,
            'Accept' => 'application/json',
        ];

        if (isset($this->payload)) {
            $defaultHeaders['Content-Type'] = 'application/json';
        }

        if (is_null($this->sdkVersion)) {
            $this->sdkVersion = Constants::SDK_VERSION;
        }

        if (isset($this->sdkVersion)) {
            $defaultHeaders[self::YOTI_SDK_VERSION] =  "{$this->sdkIdentifier}-{$this->sdkVersion}";
        }

        return array_merge($defaultHeaders, $this->headers);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateMethod()
    {
        if (empty($this->method)) {
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
            throw new \InvalidArgumentException("Unsupported HTTP Method {$this->method}", 400);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateHeaders()
    {
        foreach ($this->headers as $name => $value) {
            if (!is_string($value)) {
                throw new \InvalidArgumentException("Header value for '{$name}' must be a string");
            }
        }
    }

    /**
     * @return string
     */
    private static function generateNonce()
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
     * @throws \Yoti\Exception\RequestException
     */
    public function build()
    {
        if (empty($this->baseUrl)) {
            throw new \InvalidArgumentException('Base URL must be provided to ' . __CLASS__);
        }

        if (!$this->pemFile instanceof PemFile) {
            throw new \InvalidArgumentException('Pem file must be provided to ' . __CLASS__);
        }

        $this->validateMethod();
        $this->validateHeaders();

        // Add nonce and timestamp to the URL.
        $this
            ->withQueryParam('nonce', self::generateNonce())
            ->withQueryParam('timestamp', round(microtime(true) * 1000));

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
            new Uri($url),
            $this->getHeaders(),
            $this->payload ? $this->payload->toStream() : null
        );

        $request = new Request($message);

        if (isset($this->client)) {
            $request->setClient($this->client);
        }

        return $request;
    }
}
