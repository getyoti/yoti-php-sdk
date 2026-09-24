<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\DocScan\Session\Create\Filters\Provider;
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
     * @var bool|null
     */
    private $allowNonLatinDocuments;

    /**
     * @var bool|null
     */
    private $allowExpiredDocuments;

    /**
     * @var bool|null
     */
    private $allowDigitalIds;

    /**
     * @var Provider[]|null
     */
    private $allowedProviders;

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
     * @return $this
     */
    public function withAllowNonLatinDocuments(): self
    {
        $this->allowNonLatinDocuments = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withAllowExpiredDocuments(): self
    {
        $this->allowExpiredDocuments = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withDenyExpiredDocuments(): self
    {
        $this->allowExpiredDocuments = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function withAllowDigitalIds(): self
    {
        $this->allowDigitalIds = true;
        return $this;
    }

    /**
     * @param string[] $providerNames
     *
     * @return $this
     */
    public function withAllowedProviders(array $providerNames): self
    {
        $this->allowedProviders = array_map(
            function (string $providerName): Provider {
                return new Provider($providerName);
            },
            $providerNames
        );
        return $this;
    }

    /**
     * @return DocumentRestrictionsFilter
     */
    public function build(): DocumentFilter
    {
        Validation::notEmptyString($this->inclusion, 'inclusion');
        return new DocumentRestrictionsFilter(
            $this->inclusion,
            $this->documents,
            $this->allowNonLatinDocuments,
            $this->allowExpiredDocuments,
            $this->allowDigitalIds,
            $this->allowedProviders
        );
    }
}
