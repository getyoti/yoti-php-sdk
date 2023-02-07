<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check\Advanced;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Create\Check\Contracts\Advanced\RequestedCaMatchingStrategy;

class RequestedExactMatchingStrategy extends RequestedCaMatchingStrategy
{
    /**
     * @return bool
     */
    public function isExactMatch(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Constants::EXACT;
    }
}
