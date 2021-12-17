<?php

namespace Yoti\DocScan\Session\Instructions\Document;

class SelectedIdDocumentBuilder
{
    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $documentType;

    /**
     * Sets the country code of the {@link SelectedIdDocument}
     *
     * @param string $countryCode
     * @return $this
     */
    public function withCountryCode(string $countryCode): SelectedIdDocumentBuilder
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Sets the document type of the {@link SelectedIdDocument}
     *
     * @param string $documentType
     * @return $this
     */
    public function withDocumentType(string $documentType): SelectedIdDocumentBuilder
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return SelectedIdDocument
     */
    public function build(): SelectedIdDocument
    {
        return new SelectedIdDocument($this->countryCode, $this->documentType);
    }
}
