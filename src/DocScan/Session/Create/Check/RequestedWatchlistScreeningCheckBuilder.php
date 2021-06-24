<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

class RequestedWatchlistScreeningCheckBuilder
{
    /**
     * @var RequestedWatchlistScreeningConfig
     */
    private $config;

    /**
     * @param RequestedWatchlistScreeningConfig $config
     */
    public function withConfig(RequestedWatchlistScreeningConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * @return RequestedWatchlistScreeningCheck
     */
    public function build(): RequestedWatchlistScreeningCheck
    {
        return new RequestedWatchlistScreeningCheck($this->config);
    }
}
