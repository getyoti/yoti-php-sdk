<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use stdClass;
use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;
use Yoti\DocScan\Session\Create\Check\Contracts\Advanced\RequestedCaSources;
use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

class RequestedCustomAccountWatchlistAdvancedCaConfig extends RequestedWatchlistAdvancedCaConfig
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var bool
     */
    private $monitoring;

    /**
     * @var stdClass
     */
    private $tags;

    /**
     * @var string
     */
    private $clientRef;

    /**
     * RequestedCustomAccountWatchlistAdvancedCaConfig constructor.
     * @param bool $removeDeceased
     * @param bool $shareUrl
     * @param RequestedCaSources $sources
     * @param RequestedCaMatchingStrategy $matchingStrategy
     * @param string $apiKey
     * @param bool $monitoring
     * @param string[] $tags
     * @param string $clientRef
     */
    public function __construct(
        bool $removeDeceased,
        bool $shareUrl,
        RequestedCaSources $sources,
        RequestedCaMatchingStrategy $matchingStrategy,
        string $apiKey,
        bool $monitoring,
        string $clientRef,
        ?array $tags
    ) {
        $this->apiKey = $apiKey;
        $this->monitoring = $monitoring;
        $this->tags = !is_null($tags) ? (object)$tags : new stdClass();
        $this->clientRef = $clientRef;

        parent::__construct($removeDeceased, $shareUrl, $sources, $matchingStrategy);
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return bool
     */
    public function getMonitoring(): bool
    {
        return $this->monitoring;
    }

    /**
     * @return stdClass
     */
    public function getTags(): stdClass
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getClientRef(): string
    {
        return $this->clientRef;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::WITH_CUSTOM_ACCOUNT;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $json = parent::jsonSerialize();
        $json->api_key = $this->getApiKey();
        $json->monitoring = $this->getMonitoring();
        $json->tags = $this->getTags();
        $json->client_ref = $this->getClientRef();

        return $json;
    }
}
