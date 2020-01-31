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
     * ReportResponse constructor.
     * @param array<string, mixed> $reportData
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
}
