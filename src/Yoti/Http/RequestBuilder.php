<?php

namespace Yoti\Http;

use Yoti\Http\CurlRequestHandler;
use Yoti\YotiClient;

class RequestBuilder
{
    /**
     * @var string
     */
    private $baseUrl = YotiClient::DEFAULT_CONNECT_API;

    /**
     * @var string
     */
    private $pemString;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var Payload
     */
    private $payload = null;

    /**
     * @var string SDK Identifier
     */
    private $sdkIdentifier;

    /**
     * @param string $baseUrl
     *
     * @return RequestBuilder
     */
    public function withBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param string $pemString
     *
     * @return RequestBuilder
     */
    public function withPemString($pemString)
    {
        $this->pemString = $pemString;
        return $this;
    }

    /**
     * @param string $queryParams
     *
     * @return RequestBuilder
     */
    public function withQueryParams(array $queryParams)
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @param string $path
     *
     * @return RequestBuilder
     */
    public function withPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param Payload $payload
     *
     * @return RequestBuilder
     */
    public function withPayload(Payload $payload = null)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $sdkIdentifier
     *
     * @return RequestBuilder
     */
    public function withSdkIdentifier($sdkIdentifier)
    {
        $this->sdkIdentifier = $sdkIdentifier;
        return $this;
    }

    /**
     * @return Yoti\Http\Request
     */
    public function build()
    {
        return new Request(
            new CurlRequestHandler(
                $this->baseUrl,
                $this->pemString,
                null,
                $this->sdkIdentifier
            ),
            $this->path,
            $this->queryParams,
            $this->payload
        );
    }
}
