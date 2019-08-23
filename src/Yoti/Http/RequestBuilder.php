<?php

namespace Yoti\Http;

use Yoti\Exception\RequestException;
use Yoti\Http\CurlRequestHandler;
use Yoti\Util\PemFile;

class RequestBuilder
{
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
    private $sdkIdentifier = null;

    /**
     * @var string
     */
    private $sdkVersion = null;

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
     * @param PemFile $pemFile
     *
     * @return RequestBuilder
     */
    public function withPemFile(PemFile $pemFile)
    {
        $this->pemFile = $pemFile;
        return $this;
    }

    /**
     * @param string $pemFile
     *
     * @return RequestBuilder
     */
    public function withPemFilePath($filePath)
    {
        return $this->withPemFile(PemFile::fromFilePath($filePath));
    }

    /**
     * @param string $content
     *
     * @return RequestBuilder
     */
    public function withPemString($content)
    {
        return $this->withPemFile(PemFile::fromString($content));
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
     * @param string $sdkVersion
     *
     * @return RequestBuilder
     */
    public function withSdkVersion($sdkVersion)
    {
        $this->sdkVersion = $sdkVersion;
        return $this;
    }

    /**
     * @return Yoti\Http\AbstractRequestHandler
     */
    public function build()
    {
        if (empty($this->baseUrl)) {
            throw new RequestException('Base URL must be provided to ' . __CLASS__);
        }

        if (!$this->pemFile instanceof PemFile) {
            throw new RequestException('Pem file must be provided to ' . __CLASS__);
        }

        return new CurlRequestHandler(
            $this->baseUrl,
            (string) $this->pemFile,
            null,
            $this->sdkIdentifier,
            $this->sdkVersion
        );
    }
}
