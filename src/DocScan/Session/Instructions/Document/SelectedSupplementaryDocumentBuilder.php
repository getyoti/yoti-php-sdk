<?php

namespace Yoti\DocScan\Session\Instructions\Document;

class SelectedSupplementaryDocumentBuilder
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
     * Sets the country code of the {@link SelectedSupplementaryDocument}
     *
     * @param string $countryCode
     * @return $this
     */
    public function withCountryCode(string $countryCode): SelectedSupplementaryDocumentBuilder
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * Sets the document type of the {@link SelectedSupplementaryDocument}
     *
     * @param string $documentType
     * @return $this
     */
    public function withDocumentType(string $documentType): SelectedSupplementaryDocumentBuilder
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return SelectedSupplementaryDocument
     */
    public function build(): SelectedSupplementaryDocument
    {
        return new SelectedSupplementaryDocument($this->countryCode, $this->documentType);
    }
}
