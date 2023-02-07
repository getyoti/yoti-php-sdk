<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfig;

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
