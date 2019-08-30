<?php

namespace Yoti\Http;

use Yoti\Util\Config;
use Yoti\Util\PemFile;
use Yoti\Exception\RequestException;

abstract class AbstractRequestHandler
{
    // HTTP methods
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    // Request HttpHeader keys
    const YOTI_AUTH_HEADER_KEY = 'X-Yoti-Auth-Key';
    const YOTI_DIGEST_HEADER_KEY = 'X-Yoti-Auth-Digest';
    const YOTI_SDK_IDENTIFIER_KEY = 'X-Yoti-SDK';
    const YOTI_SDK_VERSION = 'X-Yoti-SDK-Version';

    // Default SDK Identifier.
    const YOTI_SDK_IDENTIFIER = 'PHP';

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $sdkIdentifier;

    /**
     * @var string
     */
    private $sdkVersion;

    /**
     * @var array
     */
    private $headers = [];

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

    /**
     * AbstractRequestHandler constructor.
     *
     * @param string $apiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier - deprecated - use ::setSdkIdentifier() instead.
     *
     * @throws RequestException
     */
    public function __construct($apiUrl, $pem, $sdkId = null, $sdkIdentifier = null)
    {
        $this->pemFile = PemFile::fromString($pem);
        $this->sdkId = $sdkId;
        $this->apiUrl = rtrim($apiUrl, '/');

        if (isset($sdkIdentifier)) {
            $this->setSdkIdentifier($sdkIdentifier);
        } else {
            $this->sdkIdentifier = self::YOTI_SDK_IDENTIFIER;
        }
    }

    /**
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function sendRequest(
        $endpoint,
        $httpMethod,
        Payload $payload = null,
        array $queryParams = []
    ) {
        self::validateHttpMethod($httpMethod);

        // Ensure endpoint always has a single leading slash.
        $endpoint = '/' . ltrim($endpoint, '/');

        $signedDataArr = RequestSigner::signRequest($this, $endpoint, $httpMethod, $payload, $queryParams);
        $requestHeaders = $this->generateRequestHeaders($signedDataArr[RequestSigner::SIGNED_MESSAGE_KEY]);
        $requestUrl = $this->apiUrl . $signedDataArr[RequestSigner::END_POINT_PATH_KEY];

        return $this->executeRequest($requestHeaders, $requestUrl, $httpMethod, $payload);
    }

    /**
     * Performs GET request.
     *
     * @param string $endpoint
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function get($endpoint, array $queryParams = [])
    {
        return $this->sendRequest($endpoint, self::METHOD_GET, null, $queryParams);
    }

    /**
     * Performs POST request.
     *
     * @param string $endpoint
     * @param Payload|NULL $payload
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function post($endpoint, Payload $payload = null, array $queryParams = [])
    {
        return $this->sendRequest($endpoint, self::METHOD_POST, $payload, $queryParams);
    }

    /**
     * @deprecated will be removed in version 3
     *
     * SDK ID is now added as a query param in \Yoti\YotiClient::sendRequest()
     *
     * @return string|null
     */
    public function getSdkId()
    {
        return $this->sdkId;
    }

    /**
     * @return string
     */
    public function getPem()
    {
        return (string) $this->pemFile;
    }

    /**
     * Set SDK identifier.
     *
     * Allows plugins to declare their identifier.
     *
     * @param string $sdkIdentifier
     *   SDK or Plugin identifier
     *
     * @throws RequestException
     */
    public function setSdkIdentifier($sdkIdentifier)
    {
        if (!in_array($sdkIdentifier, $this->acceptedsdkIdentifiers, true)) {
            throw new RequestException(sprintf(
                "'%s' is not in the list of accepted identifiers: %s",
                $sdkIdentifier,
                implode(', ', $this->acceptedsdkIdentifiers)
            ));
        }
        $this->sdkIdentifier = $sdkIdentifier;
    }

    /**
     * Set custom headers.
     *
     * @param string[] $headers
     *   Associative array of header names and values
     *
     * @throws RequestException
     */
    public function setHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            if (!is_string($value)) {
                throw new RequestException("Header value for '{$name}' must be a string");
            }
        }
        $this->headers = $headers;
    }

    /**
     * Set SDK version.
     *
     * Allows plugins to declare their version.
     *
     * @param string $sdkVersion
     *   SDK or Plugin version
     *
     * @throws RequestException
     */
    public function setSdkVersion($sdkVersion)
    {
        if (!is_string($sdkVersion)) {
            throw new RequestException("Yoti SDK version must be a string");
        }
        $this->sdkVersion = $sdkVersion;
    }

    /**
     * Return the request headers including the signed message.
     *
     * @param string $signedMessage
     *
     * @return array
     */
    private function generateRequestHeaders($signedMessage)
    {
        // Prepare request Http Headers
        $requestHeaders = array_merge(
            $this->headers,
            [
                self::YOTI_AUTH_HEADER_KEY => $this->pemFile->getAuthKey(),
                self::YOTI_DIGEST_HEADER_KEY => $signedMessage,
                self::YOTI_SDK_IDENTIFIER_KEY => $this->sdkIdentifier,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        if (is_null($this->sdkVersion) && ($configVersion = Config::getInstance()->get('version'))) {
            $this->sdkVersion = $configVersion;
        }

        if (isset($this->sdkVersion)) {
            $requestHeaders[self::YOTI_SDK_VERSION] =  "{$this->sdkIdentifier}-{$this->sdkVersion}";
        }

        return array_map(
            function ($name, $value) {
                return "{$name}: {$value}";
            },
            array_keys($requestHeaders),
            array_values($requestHeaders)
        );
    }

    /**
     * Check if the provided HTTP method is valid.
     *
     * @param string $httpMethod
     *
     * @throws RequestException
     */
    private static function validateHttpMethod($httpMethod)
    {
        if (!self::methodIsAllowed($httpMethod)) {
            throw new RequestException("Unsupported HTTP Method {$httpMethod}", 400);
        }
    }

    /**
     * Check the HTTP method is allowed.
     *
     * @param string $httpMethod
     *
     * @return bool
     */
    private static function methodIsAllowed($httpMethod)
    {
        $allowedMethods = [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_PATCH,
            self::METHOD_DELETE,
        ];

        return in_array($httpMethod, $allowedMethods, true);
    }

    /**
     * Execute Request against the API.
     *
     * @param string $requestUrl
     * @param array $httpHeaders
     * @param string $httpMethod
     * @param Payload|NULL $payload
     *
     * @return array
     *
     * @throws \Yoti\Exception\RequestException
     */
    abstract protected function executeRequest(array $httpHeaders, $requestUrl, $httpMethod, $payload);
}
