<?php

namespace Yoti\Http;

use Yoti\Util\PemFile;
use Yoti\Exception\RequestException;

abstract class AbstractRequestHandler
{
    /**
     * HTTP methods
     *
     * @deprecated 3.0.0 replaced by \Yoti\Http\Request
     */
    const METHOD_GET = Request::METHOD_GET;
    const METHOD_POST = Request::METHOD_POST;
    const METHOD_PUT = Request::METHOD_PUT;
    const METHOD_PATCH = Request::METHOD_PATCH;
    const METHOD_DELETE = Request::METHOD_DELETE;

    /**
     * Request HttpHeader keys
     *
     * @deprecated 3.0.0 replaced by \Yoti\Http\RequestBuilder
     */
    const YOTI_AUTH_HEADER_KEY = RequestBuilder::YOTI_AUTH_HEADER_KEY;
    const YOTI_DIGEST_HEADER_KEY = RequestBuilder::YOTI_DIGEST_HEADER_KEY;
    const YOTI_SDK_IDENTIFIER_KEY = RequestBuilder::YOTI_SDK_IDENTIFIER_KEY;
    const YOTI_SDK_VERSION = RequestBuilder::YOTI_SDK_VERSION;

    /**
     * Default SDK Identifier.
     * @deprecated 3.0.0 replaced by \Yoti\Http\RequestBuilder
     */
    const YOTI_SDK_IDENTIFIER = RequestBuilder::YOTI_SDK_IDENTIFIER;

    /**
     * @var \Yoti\Util\PemFile
     *
     * @deprecated 3.0.0
     */
    private $pemFile;

    /**
     * @var string
     *
     * @deprecated 3.0.0
     */
    private $sdkId;

    /**
     * @var string
     *
     * @deprecated 3.0.0
     */
    private $apiUrl;

    /**
     * @var string
     *
     * @deprecated 3.0.0
     */
    private $sdkIdentifier = RequestBuilder::YOTI_SDK_IDENTIFIER;

    /**
     * AbstractRequestHandler constructor.
     *
     * @deprecated 3.0.0 constructor arguments will be removed
     *
     * @param string $apiUrl
     * @param string $pem
     * @param string $sdkId
     * @param string $sdkIdentifier
     *
     * @throws RequestException
     */
    public function __construct($apiUrl = null, $pem = null, $sdkId = null, $sdkIdentifier = null)
    {
        if (isset($apiUrl)) {
            $this->apiUrl = $apiUrl;
        }
        if (isset($pem)) {
            $this->pemFile = PemFile::fromString($pem);
        }
        if (isset($sdkId)) {
            $this->sdkId = $sdkId;
        }
        if (isset($sdkIdentifier)) {
            $this->sdkIdentifier = $sdkIdentifier;
        }
    }

    /**
     * Executes provided request.
     *
     * @param Request $request
     *
     * @return array
     */
    public function execute(Request $request)
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = "{$name}: {$value}";
        }

        return $this->executeRequest(
            $headers,
            $request->getUrl(),
            $request->getMethod(),
            $request->getPayload(),
            $request
        );
    }

    /**
     * @deprecated 3.0.0 Replaced by execute()
     *
     * @param string $endpoint
     * @param string $httpMethod
     * @param Payload|NULL $payload
     * @param array queryParams
     *
     * @return array
     *
     * @throws RequestException
     */
    public function sendRequest($endpoint, $httpMethod, Payload $payload = null)
    {
        $request = (new RequestBuilder())
          ->withBaseUrl($this->apiUrl)
          ->withPemString((string) $this->pemFile)
          ->withEndpoint($endpoint)
          ->withMethod($httpMethod)
          ->withPayload($payload)
          ->withSdkIdentifier($this->sdkIdentifier)
          ->withQueryParam('appId', $this->sdkId)
          ->build();

        return $this->execute($request);
    }

    /**
     * @deprecated 3.0.0
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
     * @deprecated 3.0.0
     *
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
