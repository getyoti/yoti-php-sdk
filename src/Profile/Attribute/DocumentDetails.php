<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

use Yoti\Exception\AttributeException;

class DocumentDetails
{
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
     * DocumentDetails constructor.
     *
     * @param $value
     *
     * @throws AttributeException
     */
    public function __construct($value)
    {
        $this->validateValue($value);
        $this->parseFromValue($value);
    }

    /**
     * Return document type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Return 3 digit country code
     *
     * @return string
     */
    public function getIssuingCountry(): string
    {
        return $this->issuingCountry;
    }

    /**
     * Return document number, may contain letters.
     *
     * @return string
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * The date of expiration for the document.
     *
     * @return  null|\DateTime
     */
    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }

    /**
     * Either a country code, or the name of the issuing authority
     *
     * @return null|string
     */
    public function getIssuingAuthority(): ?string
    {
        return $this->issuingAuthority;
    }

    /**
     * @param $value
     *
     * @throws AttributeException
     */
    private function parseFromValue(string $value): void
    {
        $parsedValues = explode(' ', $value);
        $this->setType($parsedValues);
        $this->setIssuingCountry($parsedValues);
        $this->setDocumentNumber($parsedValues);
        $this->setExpirationDate($parsedValues);
        $this->setIssuingAuthority($parsedValues);
    }

    private function setType(array $parsedValues): void
    {
        $this->type = $parsedValues[self::TYPE_INDEX];
    }

    private function setIssuingCountry(array $parsedValues): void
    {
        $this->issuingCountry = $parsedValues[self::COUNTRY_INDEX];
    }

    private function setDocumentNumber(array $parsedValues): void
    {
        $this->documentNumber = $parsedValues[self::NUMBER_INDEX];
    }

    /**
     * Set expirationDate to DateTime object or NULL if the value is '-'
     *
     * @param array $parsedValues
     *
     * @throws AttributeException
     */
    private function setExpirationDate(array $parsedValues): void
    {
        $expirationDate = null;
        if (isset($parsedValues[self::EXPIRATION_INDEX])) {
            $dateStr = $parsedValues[self::EXPIRATION_INDEX];

            if ($dateStr !== '-') {
                $expirationDate = \DateTime::createFromFormat('Y-m-d', $dateStr);

                if (!$expirationDate) {
                    throw new AttributeException('Invalid Date provided');
                }
            }
        }

        $this->expirationDate = $expirationDate;
    }

    private function setIssuingAuthority(array $parsedValues): void
    {
        $value = isset($parsedValues[self::AUTHORITY_INDEX]) ? $parsedValues[self::AUTHORITY_INDEX] : null;
        $this->issuingAuthority = $value;
    }

    /**
     * @param $value
     *
     * @throws AttributeException
     */
    private function validateValue($value)
    {
        if (!preg_match(self::VALIDATION_PATTERN, $value)) {
            throw new AttributeException('Invalid value for DocumentDetails');
        }
    }
}
