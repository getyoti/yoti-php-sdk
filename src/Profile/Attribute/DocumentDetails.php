<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

use Yoti\Exception\AttributeException;
use Yoti\Exception\DateTimeException;
use Yoti\Util\DateTime;

class DocumentDetails
{
    /**
     * The values of the Document Details are in the format and order as defined in this pattern
     * e.g PASS_CARD GBR 22719564893 - CITIZENCARD, the last two are optionals
     */
    private const TYPE_INDEX = 0;
    private const COUNTRY_INDEX = 1;
    private const NUMBER_INDEX = 2;
    private const EXPIRATION_INDEX = 3;
    private const AUTHORITY_INDEX = 4;

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

        if (count($parsedValues) < 3 || in_array('', $parsedValues, true)) {
            throw new AttributeException('Invalid value for DocumentDetails');
        }

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
                try {
                    $expirationDate = DateTime::createFromFormat('Y-m-d', $dateStr);
                } catch (DateTimeException $e) {
                    throw new AttributeException('Invalid Date provided', 0, $e);
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
}
