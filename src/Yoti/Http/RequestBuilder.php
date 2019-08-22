<?php

namespace Yoti\Http;

use Yoti\Http\CurlRequestHandler;

class RequestBuilder
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $pemString;

    /**
     * @var string SDK Identifier
     */
    private $sdkIdentifier = null;

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
     * @return Yoti\Http\AbstractRequestHandler
     */
    public function build()
    {
        return new CurlRequestHandler(
            $this->baseUrl,
            $this->pemString,
            null,
            $this->sdkIdentifier
        );
    }
}
