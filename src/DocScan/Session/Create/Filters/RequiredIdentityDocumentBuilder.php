<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredIdentityDocumentBuilder
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
     * @return RequiredIdentityDocument
     */
    public function build(): RequiredIdentityDocument
    {
        return new RequiredIdentityDocument($this->filter);
    }
}
