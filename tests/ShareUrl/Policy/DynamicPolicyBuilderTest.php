<?php

namespace YotiTest\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\ShareUrl\Policy\WantedAttributeBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\DynamicPolicyBuilder
 */
class DynamicPolicyBuilderTest extends TestCase
{
    const SELFIE_AUTH_TYPE = 1;
    const PIN_AUTH_TYPE = 2;

    /**
     * @covers ::build
     * @covers ::withFamilyName
     * @covers ::withGivenNames
     * @covers ::withFullName
     * @covers ::withDateOfBirth
     * @covers ::withGender
     * @covers ::withPostalAddress
     * @covers ::withStructuredPostalAddress
     * @covers ::withNationality
     * @covers ::withPhoneNumber
     * @covers ::withSelfie
     * @covers ::withEmail
     * @covers ::withDocumentDetails
     * @covers ::withDocumentImages
     */
    public function testBuildWithAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withFamilyName()
            ->withGivenNames()
            ->withFullName()
            ->withDateOfBirth()
            ->withGender()
            ->withPostalAddress()
            ->withStructuredPostalAddress()
            ->withNationality()
            ->withPhoneNumber()
            ->withSelfie()
            ->withEmail()
            ->withDocumentDetails()
            ->withDocumentImages()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'derivation' => '', 'optional' => false],
                ['name' => 'given_names', 'derivation' => '', 'optional' => false],
                ['name' => 'full_name', 'derivation' => '', 'optional' => false],
                ['name' => 'date_of_birth', 'derivation' => '', 'optional' => false],
                ['name' => 'gender', 'derivation' => '', 'optional' => false],
                ['name' => 'postal_address', 'derivation' => '', 'optional' => false],
                ['name' => 'structured_postal_address', 'derivation' => '', 'optional' => false],
                ['name' => 'nationality', 'derivation' => '', 'optional' => false],
                ['name' => 'phone_number', 'derivation' => '', 'optional' => false],
                ['name' => 'selfie', 'derivation' => '', 'optional' => false],
                ['name' => 'email_address', 'derivation' => '', 'optional' => false],
                ['name' => 'document_details', 'derivation' => '', 'optional' => false],
                ['name' => 'document_images', 'derivation' => '', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
        $this->assertEquals(json_encode($expectedWantedAttributeData), $dynamicPolicy);
    }

    /**
     * @covers ::withWantedAttribute
     * @covers ::withFamilyName
     */
    public function testWithDuplicateAttribute()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withFamilyName()
            ->withFamilyName()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'derivation' => '', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::build
     * @covers ::withWantedAttributeByName
     */
    public function testWithAttributesByName()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedAttributeByName('family_name')
            ->withWantedAttributeByName('given_names')
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'derivation' => '', 'optional' => false],
                ['name' => 'given_names', 'derivation' => '', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::build
     * @covers ::withWantedAttribute
     */
    public function testWithAttributeObjects()
    {
        $wantedFamilyName = (new WantedAttributeBuilder())
            ->withName('family_name')
            ->build();

        $wantedGivenNames = (new WantedAttributeBuilder())
            ->withName('given_names')
            ->build();

        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedAttribute($wantedFamilyName)
            ->withWantedAttribute($wantedGivenNames)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'derivation' => '', 'optional' => false],
                ['name' => 'given_names', 'derivation' => '', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withDateOfBirth
     * @covers ::withAgeOver
     * @covers ::withAgeUnder
     */
    public function testWithAgeDerivedAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeOver(18)
            ->withAgeUnder(30)
            ->withAgeUnder(40)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'derivation' => '', 'optional' => false],
                ['name' => 'date_of_birth', 'derivation' => 'age_over:18', 'optional' => false],
                ['name' => 'date_of_birth', 'derivation' => 'age_under:30', 'optional' => false],
                ['name' => 'date_of_birth', 'derivation' => 'age_under:40', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withAgeOver
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage age must be an integer
     */
    public function testWithAgeOverIntegersOnly()
    {
        (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeOver('18')
            ->build();
    }

    /**
     * @covers ::withAgeUnder
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage age must be an integer
     */
    public function testWithAgeUnderIntegersOnly()
    {
        (new DynamicPolicyBuilder())
            ->withDateOfBirth()
            ->withAgeUnder('18')
            ->build();
    }

    /**
     * @covers ::withAgeUnder
     */
    public function testWithDuplicateAgeDerivedAttributes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withAgeUnder(30)
            ->withAgeUnder(30)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'derivation' => 'age_under:30', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication()
            ->withPinAuthentication()
            ->withWantedAuthType(99)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesTrue()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withPinAuthentication(true)
            ->withWantedAuthType(99, true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesFalse()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(false)
            ->withPinAuthentication(false)
            ->withWantedAuthType(99, false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     */
    public function testWithAuthEnabledThenDisabled()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication(false)
            ->withPinAuthentication(true)
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithSameAuthTypeAddedOnlyOnce()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication()
            ->withSelfieAuthentication(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithOnlyTwoAuthTypes()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withPinAuthentication(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoSelfieAuthAfterRemoval()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withSelfieAuthentication(true)
            ->withSelfieAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoPinAuthAfterRemoval()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withPinAuthentication(true)
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withWantedAuthType
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage wantedAuthType must be an integer
     */
    public function testWithNonIntegerAuthType()
    {
        (new DynamicPolicyBuilder())
            ->withWantedAuthType('99')
            ->build();
    }

    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithRememberMe()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedRememberMe(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => true,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }

    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithoutRememberMe()
    {
        $dynamicPolicy = (new DynamicPolicyBuilder())
            ->withWantedRememberMe(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($dynamicPolicy));
    }
}
