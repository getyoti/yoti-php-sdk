<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\Util\Validation;

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
     * @param DocumentRestriction $documentRestriction
     *
     * @return $this
     */
    public function withDocumentRestriction(DocumentRestriction $documentRestriction): self
    {
        $this->documents[] = $documentRestriction;
        return $this;
    }

    /**
     * @return DocumentRestrictionsFilter
     */
    public function build(): DocumentFilter
    {
        Validation::notEmptyString($this->inclusion, 'inclusion');
        return new DocumentRestrictionsFilter($this->inclusion, $this->documents);
    }
}
