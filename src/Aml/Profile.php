<?php

namespace Yoti\Aml;

class Profile implements \JsonSerializable
{
    const GIVEN_NAMES_ATTR  = 'given_names';
    const FAMILY_NAME_ATTR  = 'family_name';
    const SSN_ATTR          = 'ssn';
    const ADDRESS_ATTR      = 'address';

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
    public function __construct($givenNames, $familyName, Address $amlAddress, $ssn = null)
    {
        $this->givenNames = $givenNames;
        $this->familyName = $familyName;
        $this->ssn = $ssn;
        $this->amlAddress = $amlAddress;
    }

    /**
     * @return string
     */
    public function getGivenNames()
    {
        return $this->givenNames;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @return null|string
     */
    public function getSsn()
    {
        return $this->ssn;
    }

    /**
     * @return AmlAddress
     */
    public function getAmlAddress()
    {
        return $this->amlAddress;
    }

    /**
     * @param $givenNames
     */
    public function setGivenNames($givenNames)
    {
        $this->givenNames = $givenNames;
    }

    /**
     * @param $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * @param $ssn
     */
    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    }

    /**
     * @param AmlAddress $amlAddress
     */
    public function setAmlAddress(Address $amlAddress)
    {
        $this->amlAddress = $amlAddress;
    }

    /**
     * Get Aml profile data.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            self::GIVEN_NAMES_ATTR  => $this->givenNames,
            self::FAMILY_NAME_ATTR  => $this->familyName,
            self::SSN_ATTR => $this->ssn,
            self::ADDRESS_ATTR => $this->amlAddress,
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
