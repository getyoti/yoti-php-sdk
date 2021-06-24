<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

use Yoti\DocScan\Session\Retrieve\RawResultsResponse;

abstract class WatchlistSummaryResponse
{
    /**
     * @var int
     */
    private $totalHits;

    /**
     * @var RawResultsResponse
     */
    private $rawResults;

    /**
     * @var string[]
     */
    private $associatedCountryCodes;

    /**
     * @var Config
     */
    private $searchConfig;

    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return $this->totalHits;
    }

    /**
     * @return RawResultsResponse
     */
    public function getRawResults(): RawResultsResponse
    {
        return $this->rawResults;
    }

    /**
     * @return string[]
     */
    public function getAssociatedCountryCodes(): array
    {
        return $this->associatedCountryCodes;
    }

    /**
     * @return Config
     */
    public function getSearchConfig(): Config
    {
        return $this->searchConfig;
    }
}
