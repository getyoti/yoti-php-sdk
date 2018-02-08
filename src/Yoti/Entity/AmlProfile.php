<?php
namespace Yoti\Entity;

class AmlProfile
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
     * @var \Yoti\Entity\AmlAddress
     */
    private $amlAddress;

    /**
     * AmlProfile constructor.
     *
     * @param string $givenNames
     * @param string $familyName
     * @param \Yoti\Entity\AmlAddress $amlAddress
     * @param null|string $ssn
     */
    public function __construct($givenNames, $familyName, AmlAddress $amlAddress, $ssn = NULL)
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
    public function setAmlAddress(AmlAddress $amlAddress)
    {
        $this->amlAddress = $amlAddress;
    }

    /**
     * Get Aml profile data.
     *
     * @return array
     */
    public function getData()
    {
        return [
            self::GIVEN_NAMES_ATTR  => $this->givenNames,
            self::FAMILY_NAME_ATTR  => $this->familyName,
            self::SSN_ATTR => $this->ssn,
            self::ADDRESS_ATTR => $this->amlAddress->getData(),
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getData());
    }
}