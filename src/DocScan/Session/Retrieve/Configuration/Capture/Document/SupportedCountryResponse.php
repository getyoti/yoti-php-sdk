<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class SupportedCountryResponse
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var SupportedDocumentResponse[]|null
     */
    private $supportedDocuments;

    /**
     * @param array<string, mixed> $supportedCountry
     */
    public function __construct(array $supportedCountry)
    {
        $this->code = $supportedCountry['code'] ?? null;

        if (isset($supportedCountry['supported_documents'])) {
            foreach ($supportedCountry['supported_documents'] as $supportedDocument) {
                $this->supportedDocuments[] = new SupportedDocumentResponse($supportedDocument);
            }
        }
    }

    /**
     * Returns the ISO Country Code of the supported country
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Returns a list of document types that are supported for the country code
     *
     * @return SupportedDocumentResponse[]|null
     */
    public function getSupportedDocuments(): ?array
    {
        return $this->supportedDocuments;
    }
}
