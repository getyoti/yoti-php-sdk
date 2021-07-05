<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

class RequestedExactMatchingStrategyBuilder
{
    private function __construct()
    {
    }

    /**
     * @return RequestedExactMatchingStrategy
     */
    public function build(): RequestedExactMatchingStrategy
    {
        return new RequestedExactMatchingStrategy();
    }
}
