<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

class RequestedWatchlistScreeningCheckBuilder
{
    /**
     * @var RequestedWatchlistScreeningConfig|null
     */
    private $config;

    /**
     * @param RequestedWatchlistScreeningConfig|null $config
     * @return RequestedWatchlistScreeningCheckBuilder
     */
    public function withConfig(?RequestedWatchlistScreeningConfig $config): RequestedWatchlistScreeningCheckBuilder
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return RequestedWatchlistScreeningCheck
     */
    public function build(): RequestedWatchlistScreeningCheck
    {
        return new RequestedWatchlistScreeningCheck($this->config);
    }
}
