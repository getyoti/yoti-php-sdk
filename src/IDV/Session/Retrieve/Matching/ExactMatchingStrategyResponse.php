<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve\Matching;

use Yoti\IDV\Constants;
use Yoti\IDV\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

class ExactMatchingStrategyResponse extends CaMatchingStrategyResponse
{
    /**
     * @var bool
     */
    private $exactMatch;

    /**
     * @param bool $exactMatch
     */
    public function __construct(bool $exactMatch)
    {
        $this->type = Constants::EXACT;
        $this->exactMatch = $exactMatch;
    }

    /**
     * @return bool
     */
    public function isExactMatch(): bool
    {
        return $this->exactMatch;
    }
}
