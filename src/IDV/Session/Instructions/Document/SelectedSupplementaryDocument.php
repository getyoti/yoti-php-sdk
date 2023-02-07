<?php

namespace Yoti\IDV\Session\Instructions\Document;

use Yoti\IDV\Constants;

/**
 * Represents an {@code SUPPLEMENTARY_DOCUMENT} that will be used to satisfy a document requirement
 * in a given IDV session.
 */
class SelectedSupplementaryDocument extends SelectedDocument
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
     * @param string|null $countryCode
     * @param string|null $documentType
     */
    public function __construct(?string $countryCode, ?string $documentType)
    {
        parent::__construct(Constants::SUPPLEMENTARY_DOCUMENT);
        $this->countryCode = $countryCode;
        $this->documentType = $documentType;
    }

    /**
     * The country code of the selected document
     *
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * The document type of the selected document
     *
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }
}
