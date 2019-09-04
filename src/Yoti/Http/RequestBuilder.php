<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

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

    // Request HttpHeader keys
    const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';
    const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';
    const YOTI_SDK_IDENTIFIER_KEY = 'X-Yoti-SDK';
    const YOTI_SDK_VERSION = 'X-Yoti-SDK-Version';

    // Default SDK Identifier.
    const YOTI_SDK_IDENTIFIER = 'PHP';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var string
     */
    private $sdkIdentifier = self::YOTI_SDK_IDENTIFIER_KEY;

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
     * @var \Yoti\Http\AbstractRequestHandler
     */
    private $handler;

    /**
     * @param string $baseUrl
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
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
     * @param string $endpoint
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $payload
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withPayload(Payload $payload = null)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param \Yoti\Http\AbstractRequesthandler $handler
     *
     * @return \Yoti\Http\RequestBuilder
     */
    public function withHandler(AbstractRequestHandler $handler)
    {
        $this->handler = $handler;
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
            throw new RequestException(sprintf(
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
        if (!is_string($sdkVersion)) {
            throw new RequestException("Yoti SDK version must be a string");
        }
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
     * Return the request headers including the signed message.
     *
     * @param string $signedMessage
     *
     * @return array
     */
    private function getHeaders($signedMessage)
    {
        // Prepare request Http Headers
        $requestHeaders = [
            self::YOTI_AUTH_HEADER_KEY => $this->pemFile->getAuthKey(),
            self::YOTI_DIGEST_HEADER_KEY => $signedMessage,
            self::YOTI_SDK_IDENTIFIER_KEY => $this->sdkIdentifier,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if (is_null($this->sdkVersion) && ($configVersion = Config::getInstance()->get('version'))) {
            $this->sdkVersion = $configVersion;
        }

        if (isset($this->sdkVersion)) {
            $requestHeaders[self::YOTI_SDK_VERSION] =  "{$this->sdkIdentifier}-{$this->sdkVersion}";
        }

        return $requestHeaders;
    }

    /**
     * @return \Yoti\Http\Request
     *
     * @throws \Yoti\Exception\RequestException
     */
    public function build()
    {
        if (empty($this->baseUrl)) {
            throw new RequestException('Base URL must be provided to ' . __CLASS__);
        }

        if (!$this->pemFile instanceof PemFile) {
            throw new RequestException('Pem file must be provided to ' . __CLASS__);
        }

        $signedDataArr = RequestSigner::sign(
            $this->pemFile,
            $this->endpoint,
            $this->method,
            $this->payload,
            $this->queryParams
        );

        $defaultHeaders = $this->getHeaders($signedDataArr[RequestSigner::SIGNED_MESSAGE_KEY]);

        $url = rtrim($this->baseUrl, '/')  . '/' . ltrim($signedDataArr[RequestSigner::END_POINT_PATH_KEY], '/');

        $request = new Request(
            $this->method,
            $url,
            $this->queryParams,
            $this->payload,
            array_merge($defaultHeaders, $this->headers),
            $this->handler
        );

        if (isset($this->handler)) {
            $request->setHandler($this->handler);
        }

        return $request;
    }
}
