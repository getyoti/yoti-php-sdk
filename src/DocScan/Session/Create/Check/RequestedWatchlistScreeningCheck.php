<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedWatchlistScreeningCheck extends RequestedCheck
{
    /**
     * @var RequestedWatchlistScreeningConfig
     */
    private $config;

    /**
     * RequestedWatchlistScreeningCheck constructor.
     * @param RequestedWatchlistScreeningConfig $config
     */
    public function __construct(RequestedWatchlistScreeningConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return Constants::WATCHLIST_SCREENING;
    }

    /**
     * @return RequestedCheckConfigInterface|null
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
