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
     * @var \DateTime|null
     */
    private $expirationDate;

    /**
     * @var string
     */
    private $documentNumber;

    /**
     * @var string|null
     */
    private $issuingAuthority;

    /**
     * DocumentDetails constructor.
     *
     * @param string $value
     *
     * @throws \Yoti\Exception\AttributeException
     */
    public function __construct(string $value)
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
     * @param string $value
     *
     * @throws \Yoti\Exception\AttributeException
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

    /**
     * @param string[] $parsedValues
     */
    private function setType(array $parsedValues): void
    {
        $this->type = $parsedValues[self::TYPE_INDEX];
    }

    /**
     * @param string[] $parsedValues
     */
    private function setIssuingCountry(array $parsedValues): void
    {
        $this->issuingCountry = $parsedValues[self::COUNTRY_INDEX];
    }

    /**
     * @param string[] $parsedValues
     */
    private function setDocumentNumber(array $parsedValues): void
    {
        $this->documentNumber = $parsedValues[self::NUMBER_INDEX];
    }

    /**
     * Set expirationDate to DateTime object or NULL if the value is '-'
     *
     * @param string[] $parsedValues
     *
     * @throws \Yoti\Exception\AttributeException
     */
    private function setExpirationDate(array $parsedValues): void
    {
        $expirationDate = null;
        if (isset($parsedValues[self::EXPIRATION_INDEX])) {
            $dateStr = $parsedValues[self::EXPIRATION_INDEX];

            if ($dateStr !== '-') {
                $expirationDate = \DateTime::createFromFormat('Y-m-d', $dateStr);

                if ($expirationDate === false) {
                    throw new AttributeException('Invalid Date provided');
                }
            }
        }

        $this->expirationDate = $expirationDate;
    }

    /**
     * @param string[] $parsedValues
     */
    private function setIssuingAuthority(array $parsedValues): void
    {
        $value = isset($parsedValues[self::AUTHORITY_INDEX]) ? $parsedValues[self::AUTHORITY_INDEX] : null;
        $this->issuingAuthority = $value;
    }

    /**
     * @param string $value
     *
     * @throws \Yoti\Exception\AttributeException
     */
    private function validateValue($value): void
    {
        if (!(preg_match(self::VALIDATION_PATTERN, $value) === 1)) {
            throw new AttributeException('Invalid value for DocumentDetails');
        }
    }
}
