<?php

namespace YotiTest\Aml;

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Aml\Profile;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Aml\Profile
 */
class ProfileTest extends TestCase
{
    const SOME_COUNTRY_CODE = 'GBR';
    const SOME_POSTCODE = 'BN2 1TW';
    const SOME_GIVEN_NAMES = 'Edward Richard George';
    const SOME_FAMILY_NAME = 'Heath';
    const SOME_SSN = '1234';

    /**
     * @var Yoti\Aml\Profile
     */
    private $amlProfile;

    /**
     * @var Yoti\AmlAddress
     */
    private $amlAddress;

    /**
     * @var Yoti\Aml\Country
     */
    private $country;

    /**
     * Create test Aml Profile.
     */
    public function setup()
    {
        $this->country = new Country(self::SOME_COUNTRY_CODE);
        $this->amlAddress = new Address($this->country, self::SOME_POSTCODE);
        $this->amlProfile =  new Profile(
            self::SOME_GIVEN_NAMES,
            self::SOME_FAMILY_NAME,
            $this->amlAddress,
            self::SOME_SSN
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getGivenNames
     */
    public function testGetGivenNames()
    {
        $this->assertEquals(self::SOME_GIVEN_NAMES, $this->amlProfile->getGivenNames());
    }

    /**
     * @covers ::__construct
     * @covers ::setGivenNames
     */
    public function testSetGivenNames()
    {
        $someGivenNames = 'some given names';
        $this->amlProfile->setGivenNames($someGivenNames);
        $this->assertEquals($someGivenNames, $this->amlProfile->getGivenNames());
    }

    /**
     * @covers ::__construct
     * @covers ::getFamilyName
     */
    public function testGetFamilyName()
    {
        $this->assertEquals(self::SOME_FAMILY_NAME, $this->amlProfile->getFamilyName());
    }

    /**
     * @covers ::__construct
     * @covers ::setFamilyName
     */
    public function testSetFamilyName()
    {
        $someFamilyName = 'some family name';
        $this->amlProfile->setFamilyName($someFamilyName);
        $this->assertEquals($someFamilyName, $this->amlProfile->getFamilyName());
    }

    /**
     * @covers ::__construct
     * @covers ::getSsn
     */
    public function testGetSsn()
    {
        $this->assertEquals(self::SOME_SSN, $this->amlProfile->getSsn());
    }

    /**
     * @covers ::__construct
     * @covers ::setSsn
     */
    public function testSetSsn()
    {
        $someSsn = 'some ssn';
        $this->amlProfile->setSsn($someSsn);
        $this->assertEquals($someSsn, $this->amlProfile->getSsn());
    }

    /**
     * @covers ::__construct
     * @covers ::getAmlAddress
     */
    public function testGetAmlAddress()
    {
        $this->assertSame($this->amlAddress, $this->amlProfile->getAmlAddress());
    }

    /**
     * @covers ::__construct
     * @covers ::setAmlAddress
     */
    public function testSetAmlAddress()
    {
        $someAmlAddress = $this->createMock(Address::class);
        $this->amlProfile->setAmlAddress($someAmlAddress);
        $this->assertSame($someAmlAddress, $this->amlProfile->getAmlAddress());
    }

    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonSerialize()
    {
        $expectedData = [
            'given_names' => self::SOME_GIVEN_NAMES,
            'family_name' => self::SOME_FAMILY_NAME,
            'ssn' => self::SOME_SSN,
            'address' => [
                'post_code' => self::SOME_POSTCODE,
                'country' => self::SOME_COUNTRY_CODE,
            ],
        ];

        $this->assertEquals(json_encode($expectedData), json_encode($this->amlProfile));
        $this->assertEquals(json_encode($expectedData), (string) $this->amlProfile);
    }
}
