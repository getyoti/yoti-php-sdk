<?php
namespace Yoti\Entity;

use Yoti\Exception\AmlException;

class AmlProfile
{
    const GIVEN_NAMES_ATTR  = 'given_names';
    const FAMILY_NAME_ATTR  = 'family_name';
    const SSN_ATTR          = 'ssn';
    const ADDRESS_ATTR      = 'address';

    const USA_COUNTRY_CODE = 'USA';

    /**
     * @var string
     */
    private $givenNames;

    /**
     * @var string
     */
    private $familyName;

    /**
     * @var null|string
     */
    private $ssn;

    /**
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
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function __construct($givenNames, $familyName, AmlAddress $amlAddress, $ssn = '')
    {
        $this->givenNames = $givenNames;
        $this->familyName = $familyName;
        $this->ssn = $ssn;
        $this->amlAddress = $amlAddress;

        $this->validateSsn();
        $this->validatePostcode();
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

    /**
     * Check Ssn is not provided when country is not USA.
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function validateSsn()
    {
        $countryCode = $this->amlAddress->getCountry()->getCode();
        if(!empty($this->ssn) && $countryCode !== self::USA_COUNTRY_CODE)
        {
            throw new AmlException('SSN should only be provided for ' . self::USA_COUNTRY_CODE);
        }
    }

    /**
     * Check postcode is not empty when country is USA.
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function validatePostcode()
    {
        $postcode = $this->amlAddress->getPostcode();
        $countryCode = $this->amlAddress->getCountry()->getCode();
        if(empty($postcode) && $countryCode === self::USA_COUNTRY_CODE)
        {
            throw new AmlException('Postcode is required for ' . self::USA_COUNTRY_CODE);
        }
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
}