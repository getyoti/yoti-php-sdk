<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredIdentityDocumentBuilder
{
    /**
     * @var RequiredDocumentFilter
     */
    protected $filter;

    /**
     * @param RequiredDocumentFilter $filter
     *
     * @return $this
     */
    public function withFilter(RequiredDocumentFilter $filter): self
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
