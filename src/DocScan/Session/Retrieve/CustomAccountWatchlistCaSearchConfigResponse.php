<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class CustomAccountWatchlistCaSearchConfigResponse extends WatchlistAdvancedCaSearchConfigResponse
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
     * @var string[]
     */
    private $tags;

    /**
     * @var string
     */
    private $clientRef;

    /**
     * @param array<string, mixed> $searchConfig
     */
    public function __construct(array $searchConfig)
    {
        parent::__construct($searchConfig);
        $this->apiKey = $searchConfig['api_key'];
        $this->monitoring = $searchConfig['monitoring'];
        $this->clientRef = $searchConfig['client_ref'];
        $this->tags = array_key_exists('tags', $searchConfig) ? json_decode($searchConfig['tags'], true) : [];
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
     * @return string[]
     */
    public function getTags(): array
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
}
