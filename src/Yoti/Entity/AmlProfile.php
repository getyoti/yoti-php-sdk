<?php
namespace Yoti\Entity;

class AmlProfile
{
    const GIVEN_NAMES_ATTR  = 'given_names';
    const FAMILY_NAME_ATTR  = 'family_name';
    const SSN_ATTR          = 'ssn';
    const ADDRESS_ATTR      = 'address';

    private $givenNames;

    private $familyName;

    private $ssn;

    /**
     * @var \Yoti\Entity\AmlAddress
     */
    private $amlAddress;

    public function __construct($givenNames, $familyName, $ssn, AmlAddress $amlAddress)
    {
        $this->givenNames = $givenNames;
        $this->familyName = $familyName;
        $this->ssn = $ssn;
        $this->amlAddress = $amlAddress;
    }

    public function getGivenNames()
    {
        return $this->givenNames;
    }

    public function getFamilyName()
    {
        return $this->familyName;
    }

    public function getSsn()
    {
        return $this->ssn;
    }

    public function getAmlAddress()
    {
        return $this->amlAddress;
    }

    public function setGivenNames($givenNames)
    {
        $this->givenNames = $givenNames;
    }

    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    }

    public function setAmlAddress(AmlAddress $amlAddress)
    {
        $this->amlAddress = $amlAddress;
    }

    public function getData()
    {
        return [
            self::GIVEN_NAMES_ATTR  => $this->givenNames,
            self::FAMILY_NAME_ATTR  => $this->familyName,
            self::ADDRESS_ATTR => $this->amlAddress->getData(),
        ];
    }
}