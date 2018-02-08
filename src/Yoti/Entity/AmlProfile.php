<?php
namespace Yoti\Entity;

use Yoti\Exception\AmlException;

class AmlProfile
{
    const GIVEN_NAMES_ATTR  = 'given_names';
    const FAMILY_NAME_ATTR  = 'family_name';
    const SSN_ATTR          = 'ssn';
    const ADDRESS_ATTR      = 'address';

    // USA 3 letters country code
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
    public function __construct($givenNames, $familyName, AmlAddress $amlAddress, $ssn = NULL)
    {
        $this->givenNames = $givenNames;
        $this->familyName = $familyName;
        $this->ssn = $ssn;
        $this->amlAddress = $amlAddress;

        $this->validateSsn();
        $this->validatePostcode();
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
     * Check Ssn is not provided when country is not USA.
     *
     * @throws \Yoti\Exception\AmlException
     */
    public function validateSsn()
    {
        $countryCode = $this->amlAddress->getCountry()->getCode();
        // Throw an error if ssn is provided and the country code is not USA
        if(!empty($this->ssn) && strcasecmp($countryCode, self::USA_COUNTRY_CODE) !== 0)
        {
            throw new AmlException('SSN should only be provided for country ' . self::USA_COUNTRY_CODE);
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
        // Throw an error if postcode is not provided and the country code is USA
        if(empty($postcode) && strcasecmp($countryCode, self::USA_COUNTRY_CODE) === 0)
        {
            throw new AmlException('Postcode is required for country ' . self::USA_COUNTRY_CODE);
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

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getData());
    }
}