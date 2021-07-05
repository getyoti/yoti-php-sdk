<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

class RequestedWatchlistAdvancedCaCheck extends RequestedCheck
{
    /**
     * @var RequestedWatchlistAdvancedCaConfig
     */
    private $config;

    public function __construct(RequestedWatchlistAdvancedCaConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return Constants::WATCHLIST_ADVANCED_CA;
    }

    /**
     * @return RequestedCheckConfigInterface|null
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
