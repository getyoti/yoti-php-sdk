<?php

namespace Yoti\Identity;

class RequirementNotMetDetails
{
    /**
     * @var string
     */
    private $failureType;
    /**
     * @var string
     */
    private $documentType;
    /**
     * @var string
     */
    private $documentCountryIsoCode;
    /**
     * @var string
     */
    private $auditId;
    /**
     * @var string
     */
    private $details;

    /**
     * @param array<int, array<string, string>> $data
     */
    public function __construct(array $data)
    {
        $this->failureType = $data[0]["failure_type"];
        $this->details = $data[0]["details"];
        $this->auditId = $data[0]["audit_id"];
        $this->documentCountryIsoCode = $data[0]["document_country_iso_code"];
        $this->documentType = $data[0]["document_type"];
    }

    /**
     * @return string
     */
    public function getFailureType(): string
    {
        return $this->failureType;
    }
    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }
    /**
     * @return string
     */
    public function getAuditId(): string
    {
        return $this->auditId;
    }
    /**
     * @return string
     */
    public function getDocumentCountryIsoCode(): string
    {
        return $this->documentCountryIsoCode;
    }
    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }
}
