<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions\Document;

abstract class SelectedDocumentResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $documentType;

    /**
     * @param array<string, string> $document
     */
    public function __construct(array $document)
    {
        $this->type = $document['type'];
        $this->countryCode = $document['country_code'];
        $this->documentType = $document['document_type'];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }
}
