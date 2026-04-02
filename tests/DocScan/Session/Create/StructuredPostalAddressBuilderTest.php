<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\StructuredPostalAddressBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\StructuredPostalAddressBuilder
 */
class StructuredPostalAddressBuilderTest extends TestCase
{
    private const SOME_ADDRESS_FORMAT = 1;
    private const SOME_BUILDING_NUMBER = '74';
    private const SOME_ADDRESS_LINE_1 = 'AddressLine1';
    private const SOME_TOWN_CITY = 'CityName';
    private const SOME_POSTAL_CODE = 'E143RN';
    private const SOME_COUNTRY_ISO = 'GBR';
    private const SOME_COUNTRY = 'United Kingdom';
    private const SOME_FORMATTED_ADDRESS = "74\nAddressLine1\nCityName\nE143RN\nGBR";

    /**
     * @test
     * @covers ::build
     * @covers ::withAddressFormat
     * @covers ::withBuildingNumber
     * @covers ::withAddressLine1
     * @covers ::withTownCity
     * @covers ::withPostalCode
     * @covers ::withCountryIso
     * @covers ::withCountry
     * @covers ::withFormattedAddress
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::__construct
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getAddressFormat
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getBuildingNumber
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getAddressLine1
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getTownCity
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getPostalCode
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getCountryIso
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getCountry
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::getFormattedAddress
     */
    public function shouldBuildWithAllProperties()
    {
        $address = (new StructuredPostalAddressBuilder())
            ->withAddressFormat(self::SOME_ADDRESS_FORMAT)
            ->withBuildingNumber(self::SOME_BUILDING_NUMBER)
            ->withAddressLine1(self::SOME_ADDRESS_LINE_1)
            ->withTownCity(self::SOME_TOWN_CITY)
            ->withPostalCode(self::SOME_POSTAL_CODE)
            ->withCountryIso(self::SOME_COUNTRY_ISO)
            ->withCountry(self::SOME_COUNTRY)
            ->withFormattedAddress(self::SOME_FORMATTED_ADDRESS)
            ->build();

        $this->assertEquals(self::SOME_ADDRESS_FORMAT, $address->getAddressFormat());
        $this->assertEquals(self::SOME_BUILDING_NUMBER, $address->getBuildingNumber());
        $this->assertEquals(self::SOME_ADDRESS_LINE_1, $address->getAddressLine1());
        $this->assertEquals(self::SOME_TOWN_CITY, $address->getTownCity());
        $this->assertEquals(self::SOME_POSTAL_CODE, $address->getPostalCode());
        $this->assertEquals(self::SOME_COUNTRY_ISO, $address->getCountryIso());
        $this->assertEquals(self::SOME_COUNTRY, $address->getCountry());
        $this->assertEquals(self::SOME_FORMATTED_ADDRESS, $address->getFormattedAddress());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::jsonSerialize
     */
    public function shouldCorrectlySerialize()
    {
        $address = (new StructuredPostalAddressBuilder())
            ->withAddressFormat(self::SOME_ADDRESS_FORMAT)
            ->withBuildingNumber(self::SOME_BUILDING_NUMBER)
            ->withAddressLine1(self::SOME_ADDRESS_LINE_1)
            ->withTownCity(self::SOME_TOWN_CITY)
            ->withPostalCode(self::SOME_POSTAL_CODE)
            ->withCountryIso(self::SOME_COUNTRY_ISO)
            ->withCountry(self::SOME_COUNTRY)
            ->withFormattedAddress(self::SOME_FORMATTED_ADDRESS)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'address_format' => self::SOME_ADDRESS_FORMAT,
                'building_number' => self::SOME_BUILDING_NUMBER,
                'address_line1' => self::SOME_ADDRESS_LINE_1,
                'town_city' => self::SOME_TOWN_CITY,
                'postal_code' => self::SOME_POSTAL_CODE,
                'country_iso' => self::SOME_COUNTRY_ISO,
                'country' => self::SOME_COUNTRY,
                'formatted_address' => self::SOME_FORMATTED_ADDRESS,
            ]),
            json_encode($address)
        );
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\StructuredPostalAddress::jsonSerialize
     */
    public function shouldSerializeWithoutNullValues()
    {
        $address = (new StructuredPostalAddressBuilder())
            ->withBuildingNumber(self::SOME_BUILDING_NUMBER)
            ->withPostalCode(self::SOME_POSTAL_CODE)
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'building_number' => self::SOME_BUILDING_NUMBER,
                'postal_code' => self::SOME_POSTAL_CODE,
            ]),
            json_encode($address)
        );
    }
}
