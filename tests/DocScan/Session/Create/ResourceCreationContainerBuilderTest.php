<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\ApplicantProfileBuilder;
use Yoti\DocScan\Session\Create\ResourceCreationContainerBuilder;
use Yoti\DocScan\Session\Create\StructuredPostalAddressBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\ResourceCreationContainerBuilder
 */
class ResourceCreationContainerBuilderTest extends TestCase
{
    /**
     * @test
     * @covers ::build
     * @covers ::withApplicantProfile
     * @covers \Yoti\DocScan\Session\Create\ResourceCreationContainer::__construct
     * @covers \Yoti\DocScan\Session\Create\ResourceCreationContainer::getApplicantProfile
     */
    public function shouldBuildWithApplicantProfile()
    {
        $applicantProfile = (new ApplicantProfileBuilder())
            ->withFullName('John Doe')
            ->withDateOfBirth('1988-11-02')
            ->build();

        $container = (new ResourceCreationContainerBuilder())
            ->withApplicantProfile($applicantProfile)
            ->build();

        $this->assertEquals($applicantProfile, $container->getApplicantProfile());
        $this->assertEquals('John Doe', $container->getApplicantProfile()->getFullName());
        $this->assertEquals('1988-11-02', $container->getApplicantProfile()->getDateOfBirth());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\ResourceCreationContainer::jsonSerialize
     */
    public function shouldCorrectlySerializeApplicantProfile()
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

        $applicantProfile = (new ApplicantProfileBuilder())
            ->withFullName('John Doe')
            ->withDateOfBirth('1988-11-02')
            ->withNamePrefix('Mr')
            ->withStructuredPostalAddress($address)
            ->build();

        $container = (new ResourceCreationContainerBuilder())
            ->withApplicantProfile($applicantProfile)
            ->build();

        $json = json_encode($container);

        $this->assertStringContainsString('"applicant_profile"', $json);
        $this->assertStringContainsString('"full_name":"John Doe"', $json);
        $this->assertStringContainsString('"date_of_birth":"1988-11-02"', $json);
        $this->assertStringContainsString('"name_prefix":"Mr"', $json);
        $this->assertStringContainsString('"building_number":"74"', $json);
        $this->assertStringContainsString('"country_iso":"GBR"', $json);
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\ResourceCreationContainer::jsonSerialize
     */
    public function shouldSerializeWithoutNullValues()
    {
        $container = (new ResourceCreationContainerBuilder())
            ->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode(new \stdClass()),
            json_encode($container)
        );
    }
}
