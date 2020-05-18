<?php

declare(strict_types=1);

namespace Yoti\Aml;

use Yoti\Util\Json;

class Profile implements \JsonSerializable
{
    private const GIVEN_NAMES_ATTR  = 'given_names';
    private const FAMILY_NAME_ATTR  = 'family_name';
    private const SSN_ATTR          = 'ssn';
    private const ADDRESS_ATTR      = 'address';

    /**
     * Given Names.
     *
     * @var string
     */
    private $givenNames;

    /**
     * Family Name.
     *
     * @var string
     */
    private $familyName;

    /**
     * Social Security number.
     *
     * @var null|string
     */
    private $ssn;

    /**
     * Full address.
     *
     * @var \Yoti\Aml\Address
     */
    private $amlAddress;

    /**
     * Profile constructor.
     *
     * @param string $givenNames
     * @param string $familyName
     * @param \Yoti\Aml\Address $amlAddress
     * @param null|string $ssn
     */
    public function __construct($givenNames, $familyName, Address $amlAddress, string $ssn = null)
    {
        $this->givenNames = $givenNames;
        $this->familyName = $familyName;
        $this->ssn = $ssn;
        $this->amlAddress = $amlAddress;
    }

    /**
     * @return string
     */
    public function getGivenNames(): string
    {
        return $this->givenNames;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string|null
     */
    public function getSsn(): ?string
    {
        return $this->ssn;
    }

    /**
     * @return \Yoti\Aml\Address
     */
    public function getAmlAddress(): Address
    {
        return $this->amlAddress;
    }

    /**
     * Get Aml profile data.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            self::GIVEN_NAMES_ATTR  => $this->getGivenNames(),
            self::FAMILY_NAME_ATTR  => $this->getFamilyName(),
            self::SSN_ATTR => $this->getSsn(),
            self::ADDRESS_ATTR => $this->getAmlAddress(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
