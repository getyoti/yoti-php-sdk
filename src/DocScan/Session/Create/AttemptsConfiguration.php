<?php

namespace Yoti\DocScan\Session\Create;

class AttemptsConfiguration
{
    /**
     * JsonProperty "ID_DOCUMENT_TEXT_DATA_EXTRACTION"
     *
     * @var array<string, int>
     */
    public $idDocumentTextDataExtraction;

    /**
     * @param array<string, int> $idDocumentTextDataExtraction
     */
    public function __construct(array $idDocumentTextDataExtraction)
    {
        $this->idDocumentTextDataExtraction = $idDocumentTextDataExtraction;
    }

    /**
     * @return array<string, int>
     */
    public function getIdDocumentTextDataExtraction(): array
    {
        return $this->idDocumentTextDataExtraction;
    }
}
