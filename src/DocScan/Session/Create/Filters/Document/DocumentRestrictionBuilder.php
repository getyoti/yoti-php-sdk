<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

class DocumentRestrictionBuilder
{
    /**
     * @var string[]
     */
    private $documentTypes = [];

    /**
     * @var string[]
     */
    private $countryCodes = [];

    /**
     * @param string $documentType
     *
     * @return $this
     */
    public function withDocumentType(string $documentType): self
    {
        $this->documentTypes[] = $documentType;
        return $this;
    }

    /**
     * @param string $countryCode
     *
     * @return $this
     */
    public function withCountryCode(string $countryCode): self
    {
        $this->countryCodes[] = $countryCode;
        return $this;
    }

    /**
     * @return DocumentRestriction
     */
    public function build(): DocumentRestriction
    {
        return new DocumentRestriction($this->documentTypes, $this->countryCodes);
    }
}
