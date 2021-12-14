<?php

namespace Yoti\DocScan\Session\Instructions\Document;

use Yoti\DocScan\Constants;

/**
 * Represents an {@code ID_DOCUMENT} that will be used to satisfy a document requirement
 * in a given IDV session.
 */
class SelectedIdDocument extends SelectedDocument
{
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $documentType;

    /**
     * @param string $countryCode
     * @param string $documentType
     */
    public function __construct(string $countryCode, string $documentType)
    {
        parent::__construct(Constants::ID_DOCUMENT);
        $this->countryCode = $countryCode;
        $this->documentType = $documentType;
    }

    /**
     * The country code of the selected document
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * The document type of the selected document
     *
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }
}
