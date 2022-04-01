<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Matching;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\CaMatchingStrategyResponse;

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
