<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\Contracts\RequestedWatchlistAdvancedCaConfigBuilder;

class RequestedYotiAccountWatchlistAdvancedCaConfigBuilder extends RequestedWatchlistAdvancedCaConfigBuilder
{
    /**
     * @return RequestedYotiAccountWatchlistAdvancedCaConfig
     */
    public function build(): RequestedYotiAccountWatchlistAdvancedCaConfig
    {
        return new RequestedYotiAccountWatchlistAdvancedCaConfig(
            $this->removeDeceased,
            $this->shareUrl,
            $this->sources,
            $this->matchingStrategy
        );
    }
}
