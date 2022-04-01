<?php

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;

class IssuingAuthoritySubCheckBuilder
{
    /**
     * @var bool
     */
    private $requested;

    /**
     * Returns the {@link DocumentFilter} that will drive which
     * documents the sub check is performed on
     *
     * @var DocumentFilter
     */
    private $filter;

    /**
     * @param bool $requested
     * @return $this
     */
    public function withRequested(bool $requested): IssuingAuthoritySubCheckBuilder
    {
        $this->requested = $requested;

        return $this;
    }

    /**
     * @param DocumentFilter $filter
     * @return $this
     */
    public function withDocumentFilter(DocumentFilter $filter): IssuingAuthoritySubCheckBuilder
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return IssuingAuthoritySubCheck
     */
    public function build(): IssuingAuthoritySubCheck
    {
        return new IssuingAuthoritySubCheck($this->requested, $this->filter);
    }
}
