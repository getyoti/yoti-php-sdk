<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters\Document;

class DocumentRestrictionBuilder
{
    /**
     * @var string[]|null
     */
    private $documentTypes;

    /**
     * @var string[]|null
     */
    private $countryCodes;

    /**
     * @param string[] $documentTypes
     *
     * @return $this
     */
    public function withDocumentTypes(array $documentTypes): self
    {
        $this->documentTypes = $documentTypes;
        return $this;
    }

    /**
     * @param string[] $countryCodes
     *
     * @return $this
     */
    public function withCountries(array $countryCodes): self
    {
        $this->countryCodes = $countryCodes;
        return $this;
    }

    /**
     * @return DocumentRestriction
     */
    public function build(): DocumentRestriction
    {
        return new DocumentRestriction($this->countryCodes, $this->documentTypes);
    }
}
