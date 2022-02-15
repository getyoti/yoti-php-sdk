<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Filters\DocumentFilter;
use Yoti\DocScan\Session\Create\Traits\Builder\ManualCheckTrait;

class RequestedDocumentAuthenticityCheckBuilder
{
    use ManualCheckTrait;

    /**
     * @var IssuingAuthoritySubCheck|null
     */
    private $issuingAuthoritySubCheck;

    /**
     * @return $this
     */
    public function withIssuingAuthoritySubCheck(): RequestedDocumentAuthenticityCheckBuilder
    {
        $this->issuingAuthoritySubCheck = (new IssuingAuthoritySubCheckBuilder())
            ->withRequested(true)
            ->build();

        return $this;
    }

    /**
     * @param DocumentFilter $filter
     * @return $this
     */
    public function withIssuingAuthoritySubCheckAndDocumentFilter(
        DocumentFilter $filter
    ): RequestedDocumentAuthenticityCheckBuilder {
        $this->issuingAuthoritySubCheck = (new IssuingAuthoritySubCheckBuilder())
            ->withRequested(true)
            ->withDocumentFilter($filter)
            ->build();

        return $this;
    }

    /**
     * @return RequestedDocumentAuthenticityCheck
     */
    public function build(): RequestedDocumentAuthenticityCheck
    {
        $config = new RequestedDocumentAuthenticityCheckConfig($this->manualCheck, $this->issuingAuthoritySubCheck);
        return new RequestedDocumentAuthenticityCheck($config);
    }
}
