<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class ReportResponse
{
    /**
     * @var RecommendationResponse|null
     */
    private $recommendation;

    /**
     * @var BreakdownResponse[]
     */
    private $breakdown = [];

    /**
     * @var WatchlistSummaryResponse|null
     */
    private $watchListSummary;

    /**
     * ReportResponse constructor.
     * @param array<string, mixed> $reportData
     * @throws \Yoti\Exception\DateTimeException
     */
    public function __construct(array $reportData)
    {
        if (isset($reportData['recommendation'])) {
            $this->recommendation = new RecommendationResponse($reportData['recommendation']);
        }

        if (isset($reportData['breakdown'])) {
            foreach ($reportData['breakdown'] as $breakdown) {
                $this->breakdown[] = new BreakdownResponse($breakdown);
            }
        }

        if (isset($reportData['watchlist_summary'])) {
            $this->watchListSummary = new WatchlistSummaryResponse($reportData['watchlist_summary']);
        }
    }

    /**
     * @return RecommendationResponse|null
     */
    public function getRecommendation(): ?RecommendationResponse
    {
        return $this->recommendation;
    }

    /**
     * @return BreakdownResponse[]
     */
    public function getBreakdown(): array
    {
        return $this->breakdown;
    }

    /**
     * @return WatchlistSummaryResponse|null
     */
    public function getWatchlistSummary(): ?WatchlistSummaryResponse
    {
        return $this->watchListSummary;
    }
}
