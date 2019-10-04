<?php

namespace Yoti\Http;

use Yoti\Util\PemFile;
use Yoti\Exception\RequestException;
use Yoti\YotiClient;

/**
 * @deprecated 3.0.0 Replaced by \Yoti\Http\RequestHandlerInterface
 */
abstract class AbstractRequestHandler
{
    /**
     * HTTP methods
     */
    const METHOD_GET = Request::METHOD_GET;
    const METHOD_POST = Request::METHOD_POST;
    const METHOD_PUT = Request::METHOD_PUT;
    const METHOD_PATCH = Request::METHOD_PATCH;
    const METHOD_DELETE = Request::METHOD_DELETE;

    /**
     * Request HttpHeader keys
     */
    const YOTI_AUTH_HEADER_KEY = YotiClient::YOTI_AUTH_HEADER_KEY;
    const YOTI_DIGEST_HEADER_KEY = RequestBuilder::YOTI_DIGEST_HEADER_KEY;
    const YOTI_SDK_IDENTIFIER_KEY = RequestBuilder::YOTI_SDK_IDENTIFIER_KEY;
    const YOTI_SDK_VERSION = RequestBuilder::YOTI_SDK_VERSION;

    /**
     * Default SDK Identifier.
     */
    const YOTI_SDK_IDENTIFIER = RequestBuilder::YOTI_SDK_IDENTIFIER;

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
    private $sdkIdentifier = RequestBuilder::YOTI_SDK_IDENTIFIER;

    /**
     * AbstractRequestHandler constructor.
     *
     * @param string $apiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier
     */
    public function __construct($apiUrl, $pem, $sdkId = null, $sdkIdentifier = null)
    {
        $this->apiUrl = $apiUrl;
        $this->pemFile = PemFile::fromString($pem);

        if (isset($sdkId)) {
            $this->sdkId = $sdkId;
        }
        if (isset($sdkIdentifier)) {
            $this->sdkIdentifier = $sdkIdentifier;
        }
    }

    /**
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     *
     * @return array
     *
     * @throws RequestException
     */
    public function sendRequest($endpoint, $httpMethod, Payload $payload = null)
    {
        $requestBuilder = (new RequestBuilder())
          ->withBaseUrl($this->apiUrl)
          ->withPemString((string) $this->pemFile)
          ->withEndpoint($endpoint)
          ->withMethod($httpMethod)
          ->withSdkIdentifier($this->sdkIdentifier)
          ->withQueryParam('appId', $this->sdkId);

        if (isset($payload)) {
            $requestBuilder->withPayload($payload);
        }

        $request = $requestBuilder->build();

        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = "{$name}: {$value}";
        }

        return $this->executeRequest(
            $headers,
            $request->getUrl(),
            $request->getMethod(),
            $request->getPayload()
        );
    }

    /**
     * @return string
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
