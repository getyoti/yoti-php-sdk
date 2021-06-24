<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

use Yoti\DocScan\Session\Retrieve\ReportResponse;

abstract class WatchlistCheckResponse extends ProfileCheckResponse
{
    /**
     * @var null|ReportResponse
     */
    private $report;

    /**
     * @return ReportResponse|null
     */
    public function getReport(): ?ReportResponse
    {
        return $this->report;
    }
}
