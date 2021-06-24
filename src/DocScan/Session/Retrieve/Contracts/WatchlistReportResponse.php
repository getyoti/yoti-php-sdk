<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

use Yoti\DocScan\Session\Retrieve\ReportResponse;

abstract class WatchlistReportResponse extends ReportResponse
{
    /**
     * @var Summary
     */
    private $watchListSummary;

    public function getWatchlistSummary(): Summary
    {
        return $this->watchListSummary;
    }
}
