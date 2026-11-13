<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Policy;

use Yoti\Identity\Constraint\SourceConstraintBuilder;
use Yoti\Identity\Policy\PolicyBuilder;
use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Identity\Policy\WantedAttributeBuilder;
use Yoti\Profile\UserProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Policy\PolicyBuilder
 */
class PolicyBuilderTest extends TestCase
{
    private const SELFIE_AUTH_TYPE = 1;
    private const PIN_AUTH_TYPE = 2;

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
     * @covers \Yoti\Identity\Policy\Policy::__construct
     * @covers \Yoti\Identity\Policy\Policy::jsonSerialize
     */
    public function testBuildWithAttributes()
    {
        $policy = (new PolicyBuilder())
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
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
                ['name' => 'full_name', 'optional' => false],
                ['name' => 'date_of_birth', 'optional' => false],
                ['name' => 'gender', 'optional' => false],
                ['name' => 'postal_address', 'optional' => false],
                ['name' => 'structured_postal_address', 'optional' => false],
                ['name' => 'nationality', 'optional' => false],
                ['name' => 'phone_number', 'optional' => false],
                ['name' => 'selfie', 'optional' => false],
                ['name' => 'email_address', 'optional' => false],
                ['name' => 'document_details', 'optional' => false],
                ['name' => 'document_images', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withWantedAttributeByName
     */
    public function testWithWantedAttributeByNameWithConstraints()
    {
        $someAttributeName = 'some_attribute_name';
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME'))
            ->build();

        $constraints = [
            $sourceConstraint,
        ];

        $policy = (new PolicyBuilder())
            ->withWantedAttributeByName($someAttributeName, $constraints, true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                [
                    'name' => $someAttributeName,
                    'optional' => false,
                    "constraints" => [
                        [
                            "type" => "SOURCE",
                            "preferred_sources" => [
                                "anchors" => [
                                    [
                                        "name" => "SOME",
                                        "sub_type" => "",
                                    ]
                                ],
                                "soft_preference" => false,
                            ],
                        ],
                    ],
                    "accept_self_asserted" => true,
                ],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedWantedAttributeData),
            json_encode($policy)
        );
    }

    /**
     * @covers ::withWantedAttribute
     * @covers ::withFamilyName
     */
    public function testWithDuplicateAttribute()
    {
        $policy = (new PolicyBuilder())
            ->withFamilyName()
            ->withFamilyName()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withWantedAttribute
     * @covers ::withFamilyName
     */
    public function testWithDuplicateAttributeDifferentConstraints()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME'))
            ->build();

        $sourceConstraint2 = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME_2'))
            ->build();


        $policy = (new PolicyBuilder())
            ->withFamilyName()
            ->withFamilyName([$sourceConstraint])
            ->withFamilyName([$sourceConstraint2])
            ->build();

        $jsonData = $policy->jsonSerialize();

        $this->assertCount(3, $jsonData->wanted);
        foreach ($jsonData->wanted as $wantedAttribute) {
            $this->assertEquals('family_name', $wantedAttribute->getName());
        }
    }

    /**
     * @covers ::build
     * @covers ::withWantedAttributeByName
     */
    public function testWithWantedAttributeByName()
    {
        $policy = (new PolicyBuilder())
            ->withWantedAttributeByName('family_name')
            ->withWantedAttributeByName('given_names')
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
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

        $policy = (new PolicyBuilder())
            ->withWantedAttribute($wantedFamilyName)
            ->withWantedAttribute($wantedGivenNames)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'family_name', 'optional' => false],
                ['name' => 'given_names', 'optional' => false],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withDateOfBirth
     * @covers ::withAgeOver
     * @covers ::withAgeUnder
     * @covers ::withAgeDerivedAttribute
     */
    public function testWithAgeDerivedAttributes()
    {
        $policy = (new PolicyBuilder())
            ->withDateOfBirth()
            ->withAgeOver(18)
            ->withAgeUnder(30)
            ->withAgeUnder(40)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'optional' => false],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_over:18'],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:30'],
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:40'],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withAgeDerivedAttribute
     */
    public function testWithAgeDerivedAttributesWithConstraints()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME'))
            ->build();


        $policy = (new PolicyBuilder())
            ->withAgeDerivedAttribute(UserProfile::AGE_OVER . '18', [$sourceConstraint])
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                [
                    'name' => 'date_of_birth',
                    'optional' => false,
                    'derivation' => 'age_over:18',
                    "constraints" => [
                        [
                            "type" => "SOURCE",
                            "preferred_sources" => [
                                "anchors" => [
                                    [
                                        "name" => "SOME",
                                        "sub_type" => "",
                                    ]
                                ],
                                "soft_preference" => false,
                            ],
                        ],
                    ],
                ],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedWantedAttributeData),
            json_encode($policy)
        );
    }


    /**
     * @covers ::withAgeUnder
     * @covers ::withAgeDerivedAttribute
     * @covers ::withWantedAttribute
     */
    public function testWithDuplicateAgeDerivedAttributes()
    {
        $policy = (new PolicyBuilder())
            ->withAgeUnder(30)
            ->withAgeUnder(30)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                ['name' => 'date_of_birth', 'optional' => false, 'derivation' => 'age_under:30'],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypes()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withPinAuthentication()
            ->withWantedAuthType(99)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesTrue()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withPinAuthentication()
            ->withWantedAuthType(99)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE, 99],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     * @covers ::withWantedAuthType
     */
    public function testWithAuthTypesFalse()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication(false)
            ->withPinAuthentication(false)
            ->withWantedAuthType(99, false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     * @covers ::withPinAuthentication
     */
    public function testWithAuthEnabledThenDisabled()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withSelfieAuthentication(false)
            ->withPinAuthentication()
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithSameAuthTypeAddedOnlyOnce()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withSelfieAuthentication()
            ->withSelfieAuthentication()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithOnlyTwoAuthTypes()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withPinAuthentication()
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [self::SELFIE_AUTH_TYPE, self::PIN_AUTH_TYPE],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoSelfieAuthAfterRemoval()
    {
        $policy = (new PolicyBuilder())
            ->withSelfieAuthentication()
            ->withSelfieAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withSelfieAuthentication
     */
    public function testWithNoPinAuthAfterRemoval()
    {
        $policy = (new PolicyBuilder())
            ->withPinAuthentication()
            ->withPinAuthentication(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }


    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithRememberMe()
    {
        $policy = (new PolicyBuilder())
            ->withWantedRememberMe(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => true,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withWantedRememberMe
     */
    public function testWithoutRememberMe()
    {
        $policy = (new PolicyBuilder())
            ->withWantedRememberMe(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withWantedRememberMeOptional
     */
    public function testWithRememberMeOptional()
    {
        $policy = (new PolicyBuilder())
            ->withWantedRememberMeOptional(true)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => true,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withWantedRememberMeOptional
     */
    public function testWithoutRememberMeOptional()
    {
        $policy = (new PolicyBuilder())
            ->withWantedRememberMeOptional(false)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null
        ];

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
    }

    /**
     * @covers ::withIdentityProfileRequirements
     * @covers \Yoti\Identity\Policy\Policy::__construct
     * @covers \Yoti\Identity\Policy\Policy::getIdentityProfileRequirements
     * @covers \Yoti\Identity\Policy\Policy::jsonSerialize
     */
    public function testWithIdentityProfileRequirements()
    {
        $identityProfileSample = (object)[
            'trust_framework' => 'UK_TFIDA',
            'scheme' => [
                'type' => 'DBS',
                'objective' => 'STANDARD'
            ]
        ];

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => $identityProfileSample,
            'advanced_identity_profile_requirements' => null
        ];

        $policy = (new PolicyBuilder())
            ->withIdentityProfileRequirements($identityProfileSample)
            ->build();

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
        $this->assertEquals($identityProfileSample, $policy->getIdentityProfileRequirements());
    }

    /**
     * @covers ::withAdvancedIdentityProfileRequirements
     * @covers \Yoti\Identity\Policy\Policy::__construct
     * @covers \Yoti\Identity\Policy\Policy::getAdvancedIdentityProfileRequirements
     * @covers \Yoti\Identity\Policy\Policy::jsonSerialize
     */
    public function testWithAdvancedIdentityProfileRequirements()
    {
        $advancedIdentityProfileSample =
            (object)[
                "profiles" => [(object)[

                    "trust_framework" => "YOTI_GLOBAL",
                    "schemes" => [(object)[

                        "label" => "identity-AL-L1",
                        "type" => "IDENTITY",
                        "objective" => "AL_L1"
                    ],
                        [
                            "label" => "identity-AL-M1",
                            "type" => "IDENTITY",
                            "objective" => "AL_M1"
                        ]
                    ]
                ]
                ]
            ]
        ;

        $expectedWantedAttributeData = [
            'wanted' => [],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => $advancedIdentityProfileSample
        ];

        $policy = (new PolicyBuilder())
            ->withAdvancedIdentityProfileRequirements($advancedIdentityProfileSample)
            ->build();

        $this->assertEquals(json_encode($expectedWantedAttributeData), json_encode($policy));
        $this->assertEquals($advancedIdentityProfileSample, $policy->getAdvancedIdentityProfileRequirements());
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     * @covers \Yoti\Identity\Policy\WantedAttribute::__construct
     * @covers \Yoti\Identity\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\Identity\Policy\WantedAttribute::getAlternativeNames
     */
    public function testWithEstimatedAgeOverProducesCorrectWantedAttribute()
    {
        $policy = (new PolicyBuilder())
            ->withEstimatedAgeOver(18, 5)
            ->build();

        $expectedWantedAttributeData = [
            'wanted' => [
                [
                    'name' => 'estimated_age',
                    'optional' => false,
                    'derivation' => 'age_over:18:5',
                    'alternative_names' => ['date_of_birth'],
                    'accept_self_asserted' => false,
                ],
            ],
            'wanted_auth_types' => [],
            'wanted_remember_me' => false,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => null,
            'advanced_identity_profile_requirements' => null,
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedWantedAttributeData),
            json_encode($policy)
        );
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverBoundaryValuesValid()
    {
        // age 99, buffer 20 should be valid
        $policy = (new PolicyBuilder())
            ->withEstimatedAgeOver(99, 20)
            ->build();

        $jsonData = $policy->jsonSerialize();
        $this->assertCount(1, $jsonData->wanted);

        $attr = $jsonData->wanted[0];
        $this->assertEquals('estimated_age', $attr->getName());
        $this->assertEquals('age_over:99:20', $attr->getDerivation());
        $this->assertEquals(['date_of_birth'], $attr->getAlternativeNames());

        // age 1, buffer 0 should be valid
        $policy2 = (new PolicyBuilder())
            ->withEstimatedAgeOver(1, 0)
            ->build();

        $jsonData2 = $policy2->jsonSerialize();
        $this->assertCount(1, $jsonData2->wanted);
        $attr2 = $jsonData2->wanted[0];
        $this->assertEquals('age_over:1:0', $attr2->getDerivation());
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverThrowsForAgeOfInterestTooHigh()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('age of interest must be between 1 and 99');

        (new PolicyBuilder())->withEstimatedAgeOver(100, 5);
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverThrowsForAgeOfInterestZero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('age of interest must be between 1 and 99');

        (new PolicyBuilder())->withEstimatedAgeOver(0, 5);
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverThrowsForNegativeAgeOfInterest()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('age of interest must be between 1 and 99');

        (new PolicyBuilder())->withEstimatedAgeOver(-1, 5);
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverThrowsForBufferTooHigh()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('buffer must be between 0 and 20');

        (new PolicyBuilder())->withEstimatedAgeOver(18, 21);
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverThrowsForNegativeBuffer()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('buffer must be between 0 and 20');

        (new PolicyBuilder())->withEstimatedAgeOver(18, -1);
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverComposesCorrectlyWithOtherAttributes()
    {
        $policy = (new PolicyBuilder())
            ->withFamilyName()
            ->withEstimatedAgeOver(25, 3)
            ->withEmail()
            ->build();

        $jsonData = $policy->jsonSerialize();
        $this->assertCount(3, $jsonData->wanted);

        // family_name at index 0
        $this->assertEquals('family_name', $jsonData->wanted[0]->getName());

        // estimated_age at index 1
        $estimatedAgeAttr = $jsonData->wanted[1];
        $this->assertEquals('estimated_age', $estimatedAgeAttr->getName());
        $this->assertEquals('age_over:25:3', $estimatedAgeAttr->getDerivation());
        $this->assertEquals(['date_of_birth'], $estimatedAgeAttr->getAlternativeNames());
        $this->assertFalse($estimatedAgeAttr->getAcceptSelfAsserted());
        $this->assertFalse($estimatedAgeAttr->getOptional());

        // email_address at index 2
        $this->assertEquals('email_address', $jsonData->wanted[2]->getName());
    }

    /**
     * @test
     * @covers ::withEstimatedAgeOver
     */
    public function testWithEstimatedAgeOverDerivationFormat()
    {
        $policy = (new PolicyBuilder())
            ->withEstimatedAgeOver(30, 10)
            ->build();

        $jsonString = json_encode($policy);
        $jsonData = json_decode($jsonString, true);

        $attr = $jsonData['wanted'][0];
        // Verify no spaces in derivation
        $this->assertEquals('age_over:30:10', $attr['derivation']);
        // Verify alternative_names is snake_case and has date_of_birth
        $this->assertEquals(['date_of_birth'], $attr['alternative_names']);
        // Verify accept_self_asserted is false
        $this->assertFalse($attr['accept_self_asserted']);
        // Verify optional is false
        $this->assertFalse($attr['optional']);
    }
}
