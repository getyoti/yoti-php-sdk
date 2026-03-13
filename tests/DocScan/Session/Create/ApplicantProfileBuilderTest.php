<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\ApplicantProfileBuilder;
use Yoti\DocScan\Session\Create\StructuredPostalAddressBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\ApplicantProfileBuilder
 */
class ApplicantProfileBuilderTest extends TestCase
{
    private const SOME_FULL_NAME = 'John Doe';
    private const SOME_DATE_OF_BIRTH = '1988-11-02';
    private const SOME_NAME_PREFIX = 'Mr';

    /**
     * @test
     * @covers ::build
     * @covers ::withFullName
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::__construct
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::getFullName
     */
    public function shouldBuildWithFullName()
    {
        $profile = (new ApplicantProfileBuilder())
            ->withFullName(self::SOME_FULL_NAME)
            ->build();

        $this->assertEquals(self::SOME_FULL_NAME, $profile->getFullName());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withDateOfBirth
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::__construct
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::getDateOfBirth
     */
    public function shouldBuildWithDateOfBirth()
    {
        $profile = (new ApplicantProfileBuilder())
            ->withDateOfBirth(self::SOME_DATE_OF_BIRTH)
            ->build();

        $this->assertEquals(self::SOME_DATE_OF_BIRTH, $profile->getDateOfBirth());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withNamePrefix
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::__construct
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::getNamePrefix
     */
    public function shouldBuildWithNamePrefix()
    {
        $profile = (new ApplicantProfileBuilder())
            ->withNamePrefix(self::SOME_NAME_PREFIX)
            ->build();

        $this->assertEquals(self::SOME_NAME_PREFIX, $profile->getNamePrefix());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withStructuredPostalAddress
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::__construct
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::getStructuredPostalAddress
     */
    public function shouldBuildWithStructuredPostalAddress()
    {
        $address = (new StructuredPostalAddressBuilder())
            ->withBuildingNumber('74')
            ->withPostalCode('E143RN')
            ->build();

        $profile = (new ApplicantProfileBuilder())
            ->withStructuredPostalAddress($address)
            ->build();

        $this->assertEquals('74', $profile->getStructuredPostalAddress()->getBuildingNumber());
        $this->assertEquals('E143RN', $profile->getStructuredPostalAddress()->getPostalCode());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::jsonSerialize
     */
    public function shouldCorrectlySerializeWithAllProperties()
    {
        $address = (new StructuredPostalAddressBuilder())
            ->withAddressFormat(1)
            ->withBuildingNumber('74')
            ->withAddressLine1('AddressLine1')
            ->withTownCity('CityName')
            ->withPostalCode('E143RN')
            ->withCountryIso('GBR')
            ->withCountry('United Kingdom')
            ->build();

        $profile = (new ApplicantProfileBuilder())
            ->withFullName(self::SOME_FULL_NAME)
            ->withDateOfBirth(self::SOME_DATE_OF_BIRTH)
            ->withNamePrefix(self::SOME_NAME_PREFIX)
            ->withStructuredPostalAddress($address)
            ->build();

        $json = json_encode($profile);

        $this->assertStringContainsString('"full_name":"John Doe"', $json);
        $this->assertStringContainsString('"date_of_birth":"1988-11-02"', $json);
        $this->assertStringContainsString('"name_prefix":"Mr"', $json);
        $this->assertStringContainsString('"structured_postal_address"', $json);
        $this->assertStringContainsString('"building_number":"74"', $json);
        $this->assertStringContainsString('"country_iso":"GBR"', $json);
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\ApplicantProfile::jsonSerialize
     */
    public function shouldSerializeWithoutNullValues()
    {
        $profile = (new ApplicantProfileBuilder())
            ->withFullName(self::SOME_FULL_NAME)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'full_name' => self::SOME_FULL_NAME,
            ]),
            json_encode($profile)
        );
    }
}
