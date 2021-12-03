<?php

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;

class IssuingAuthoritySubCheck
{
    /**
     * @var bool|null
     */
    private $requested;

    /**
     * Returns the {@link DocumentFilter} that will drive which
     * documents the sub check is performed on
     *
     * @var DocumentFilter|null
     */
    private $filter;

    /**
     * @param bool|null $requested
     * @param DocumentFilter|null $documentFilter
     */
    public function __construct(?bool $requested, ?DocumentFilter $documentFilter)
    {
        $this->requested = $requested;
        $this->filter = $documentFilter;
    }

    /**
     * Returns if the issuing authority sub check has been requested
     *
     * @return bool|null
     */
    public function isRequested(): ?bool
    {
        return $this->requested;
    }

    /**
     * @return DocumentFilter|null
     */
    public function getFilter(): ?DocumentFilter
    {
        return $this->filter;
    }
}
