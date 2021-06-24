<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\Contracts\WatchlistSearchConfigResponse;

class WatchlistScreeningSearchConfigResponse extends WatchlistSearchConfigResponse
{
    /**
     * @var string[]
     */
    private $categories;

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
