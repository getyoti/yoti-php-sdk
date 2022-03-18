<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Contracts\WatchlistSearchConfigResponse;
use Yoti\Exception\DateTimeException;

class WatchlistSummaryResponse
{
    /**
     * @var int
     */
    private $totalHits;

    /**
     * @var RawResultsResponse|null
     */
    private $rawResults;

    /**
     * @var string[]|null
     */
    private $associatedCountryCodes;

    /**
     * @var WatchlistSearchConfigResponse
     */
    private $searchConfig;

    /**
     * @param array<string, mixed> $watchListSummary
     * @throws DateTimeException
     */
    public function __construct(array $watchListSummary)
    {
        $this->totalHits = $watchListSummary['total_hits'];

        if (isset($watchListSummary['raw_results'])) {
            $this->rawResults = new RawResultsResponse($watchListSummary['raw_results']);
        }
        if (isset($watchListSummary['associated_country_codes'])) {
            $this->associatedCountryCodes = $watchListSummary['associated_country_codes'];
        }

        $this->setSearchConfig($watchListSummary['search_config']);
    }

    /**
     * @param array<string, mixed> $searchConfig
     */
    private function setSearchConfig(array $searchConfig): void
    {
        if (isset($searchConfig['type'])) {
            if ($searchConfig['type'] == Constants::WITH_YOTI_ACCOUNT) {
                $this->searchConfig = new YotiAccountWatchlistCaSearchConfigResponse($searchConfig);
            }
            if ($searchConfig['type'] == Constants::WITH_CUSTOM_ACCOUNT) {
                $this->searchConfig = new CustomAccountWatchlistCaSearchConfigResponse($searchConfig);
            }
        } else {
            $this->searchConfig = new WatchlistScreeningSearchConfigResponse($searchConfig);
        }
    }

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
    public function getRawResults(): ?RawResultsResponse
    {
        return $this->rawResults;
    }

    /**
     * @return string[]
     */
    public function getAssociatedCountryCodes(): ?array
    {
        return $this->associatedCountryCodes;
    }

    /**
     * @return WatchlistSearchConfigResponse
     */
    public function getSearchConfig(): WatchlistSearchConfigResponse
    {
        return $this->searchConfig;
    }
}
