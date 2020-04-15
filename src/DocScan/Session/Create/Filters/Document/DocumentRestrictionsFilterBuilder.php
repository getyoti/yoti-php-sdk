<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\RequiredDocumentFilter;

class DocumentRestrictionsFilterBuilder
{
    /**
     * @var string
     */
    private $inclusion;

    /**
     * @var DocumentRestriction[]
     */
    private $documents = [];

    /**
     * @return $this
     */
    public function forWhitelist(): self
    {
        $this->inclusion = Constants::INCLUSION_WHITELIST;
        return $this;
    }

    /**
     * @return $this
     */
    public function forBlacklist(): self
    {
        $this->inclusion = Constants::INCLUSION_BLACKLIST;
        return $this;
    }


    /**
     * @return $this
     */
    public function withDocumentRestriction(DocumentRestriction $document): self
    {
        $this->documents[] = $document;
        return $this;
    }

    /**
     * @return DocumentRestrictionsFilter
     */
    public function build(): RequiredDocumentFilter
    {
        return new DocumentRestrictionsFilter($this->inclusion, $this->documents);
    }
}
