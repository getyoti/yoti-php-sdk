<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

abstract class RequiredDocumentBuilder
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
     * @return RequiredDocument
     */
    abstract public function build(): RequiredDocument;
}
