<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredIdDocumentBuilder
{
    /**
     * @var DocumentFilter
     */
    protected $filter;

    /**
     * @param DocumentFilter $filter
     *
     * @return $this
     */
    public function withFilter(DocumentFilter $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return RequiredIdDocument
     */
    public function build(): RequiredIdDocument
    {
        return new RequiredIdDocument($this->filter);
    }
}
