<?php

namespace Yoti\Service\ShareUrl;

use Yoti\Exception\ShareUrlException;
use Yoti\Http\Payload;
use Yoti\Util\PemFile;
use Yoti\Http\RequestBuilder;
use Yoti\ShareUrl\DynamicScenario;
use Yoti\Util\Config;
use Yoti\Util\Json;

class DynamicSharingService
{
    /**
     * @var \Yoti\Util\Config
     */
    private $config;

    /**
     * @param \Yoti\Util\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Yoti\ShareUrl\DynamicScenario $dynamicScenario
     *
     * @return \Yoti\Service\ShareUrl\ShareUrlResult
     *
     * @throws \Yoti\Exception\ShareUrlException
     */
    public function createShareUrl(DynamicScenario $dynamicScenario, PemFile $pemFile, string $sdkId): ShareUrlResult
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getConnectApiUrl())
            ->withEndpoint(sprintf('/qrcodes/apps/%s', $sdkId))
            ->withQueryParam('appId', $sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($dynamicScenario))
            ->withPemFile($pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new ShareUrlException("Server responded with {$httpCode}");
        }

        return new ShareUrlResult(Json::decode($response->getBody()));
    }
}
