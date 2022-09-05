<?php

namespace Yoti\DocScan\Session\Create;

class AttemptsConfiguration
{
    /**
     * JsonProperty "ID_DOCUMENT_TEXT_DATA_EXTRACTION"
     *
     * @var array<string, int>
     */
    public $ID_DOCUMENT_TEXT_DATA_EXTRACTION;

    /**
     * @param array<string, int> $idDocumentTextDataExtraction
     */
    public function __construct(array $idDocumentTextDataExtraction)
    {
        $this->ID_DOCUMENT_TEXT_DATA_EXTRACTION = $idDocumentTextDataExtraction;
    }

    /**
     * @return array<string, int>
     */
    public function getIdDocumentTextDataExtraction(): array
    {
        return $this->ID_DOCUMENT_TEXT_DATA_EXTRACTION;
    }
}
