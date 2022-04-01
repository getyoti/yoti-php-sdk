<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

class RequestedYotiAccountWatchlistAdvancedCaConfig extends RequestedWatchlistAdvancedCaConfig
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::WITH_YOTI_ACCOUNT;
    }
}
