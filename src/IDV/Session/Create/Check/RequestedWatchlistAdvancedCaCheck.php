<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

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
    public function getType(): string
    {
        return Constants::WATCHLIST_ADVANCED_CA;
    }

    /**
     * @return RequestedCheckConfigInterface|null
     */
    public function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
