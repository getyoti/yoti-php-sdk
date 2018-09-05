<?php
namespace Yoti\Entity;

class DocumentDetails
{
    const DOCUMENT_TYPE_PASSPORT = "PASSPORT";
    const DOCUMENT_TYPE_DRIVING_LICENCE = "DRIVING_LICENCE";
    const DOCUMENT_TYPE_AADHAAR = "AADHAAR";
    const DOCUMENT_TYPE_PASS_CARD = "PASS_CARD";

    /**
     * The values of the Document Details are in the format and order as defined in this pattern
     * e.g PASS_CARD GBR 22719564893 - CITIZENCARD, the last two are optionals
     */
    const VALIDATION_PATTERN = '/^([A-Za-z_]*) ([A-Za-z]{3}) ([A-Za-z0-9]{1}).*$/';
    const TYPE_INDEX = 0;
    const COUNTRY_INDEX = 1;
    const NUMBER_INDEX = 2;
    const EXPIRATION_INDEX = 3;
    const AUTHORITY_INDEX = 4;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $issuingCountry;

    /**
     * @var \DateTime
     */
    private $expirationDate;

    /**
     * @var string
     */
    private $documentNumber;

    /**
     * @var string
     */
    private $issuingAuthority;

    /**
     * @var array
     */
    private $parsedValues = [];

    public function __construct($value)
    {
        $this->parseFromValue($value);
    }

    /**
     * Return document type.
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return 3 digit country code
     *
     * @return null|string
     */
    public function getIssuingCountry()
    {
        return $this->issuingCountry;
    }

    /**
     * Return document number, may contain letters.
     *
     * @return null|string
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * The date of expiration for the document.
     *
     * @return  null|\DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Either a country code, or the name of the issuing authority
     *
     * @return null|string
     */
    public function getIssuingAuthority()
    {
        return $this->issuingAuthority;
    }

    /**
     * @param $value
     */
    private function parseFromValue($value)
    {
        if (!$this->isValidFormat($value)) {
            return;
        }

        $this->parsedValues = explode(' ', $value);
        $this->setType();
        $this->setIssuingCountry();
        $this->setDocumentNumber();
        $this->setExpirationDate();
        $this->setIssuingAuthority();
    }

    private function setType()
    {
        $this->type = $this->parsedValues[self::TYPE_INDEX];
    }

    private function setIssuingCountry()
    {
        $this->issuingCountry = $this->parsedValues[self::COUNTRY_INDEX];
    }

    private function setDocumentNumber()
    {
        $this->documentNumber = $this->parsedValues[self::NUMBER_INDEX];
    }

    private function setExpirationDate()
    {
        $expirationDate = NULL;
        if (isset($this->parsedValues[self::EXPIRATION_INDEX])) {
            $dateStr = $this->parsedValues[self::EXPIRATION_INDEX];
            $expirationDate = \DateTime::createFromFormat('Y-m-d', $dateStr);
        }
        $this->expirationDate = $expirationDate?: NULL;
    }

    private function setIssuingAuthority()
    {
        $value = isset($this->parsedValues[self::AUTHORITY_INDEX])? $this->parsedValues[self::AUTHORITY_INDEX] : NULL;
        $this->issuingAuthority = $value;
    }

    private function isValidFormat($value)
    {
        return preg_match(self::VALIDATION_PATTERN, $value);
    }
}