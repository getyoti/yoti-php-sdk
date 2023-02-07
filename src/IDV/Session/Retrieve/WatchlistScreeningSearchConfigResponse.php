<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\Contracts\WatchlistSearchConfigResponse;

class WatchlistScreeningSearchConfigResponse extends WatchlistSearchConfigResponse
{
    /**
     * @var string[]
     */
    private $categories;

    /**
     * @param array<string, array> $searchConfig
     */
    public function __construct(array $searchConfig)
    {
        $this->categories = $searchConfig['categories'];
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
