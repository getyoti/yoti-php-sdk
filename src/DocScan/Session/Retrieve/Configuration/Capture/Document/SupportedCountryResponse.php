<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class SupportedCountryResponse
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var array<int, SupportedDocumentResponse>
     */
    private $supportedDocuments;

    /**
     * Returns the ISO Country Code of the supported country
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Returns a list of document types that are supported for the country code
     *
     * @return SupportedDocumentResponse[]
     */
    public function getSupportedDocuments(): array
    {
        return $this->supportedDocuments;
    }
}
