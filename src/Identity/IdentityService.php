<?php

namespace Yoti\Identity;

use Yoti\Constants;
use Yoti\Exception\IdentityException;
use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;
use Yoti\Util\Json;
use Yoti\Util\PemFile;

class IdentityService
{
    private const IDENTITY_SESSION_CREATION_TEMPLATE = '/v2/sessions';

    private string $sdkId;

    private PemFile $pemFile;

    private Config $config;

    public function __construct(string $sdkId, PemFile $pemFile, Config $config)
    {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->config = $config;
    }

    public function createShareSession(ShareSessionRequest $shareSessionRequest): ShareSession
    {
        $response = (new RequestBuilder($this->config))
            ->withBaseUrl($this->config->getApiUrl() ?? Constants::API_URL)
            ->withEndpoint(self::IDENTITY_SESSION_CREATION_TEMPLATE)
            ->withHeader('X-Yoti-Auth-Id', $this->sdkId)
            ->withPost()
            ->withPayload(Payload::fromJsonData($shareSessionRequest))
            ->withPemFile($this->pemFile)
            ->build()
            ->execute();

        $httpCode = $response->getStatusCode();
        if ($httpCode < 200 || $httpCode > 299) {
            throw new IdentityException("Server responded with {$httpCode}", $response);
        }

        return new ShareSession(Json::decode((string)$response->getBody()));
    }
}
