<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request;

use Yoti\Sandbox\Profile\Request\Attribute\SandboxAgeVerification;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxDocumentDetails;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\Profile\Request\TokenRequestBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\TokenRequestBuilder
 */
class TokenRequestBuilderTest extends TestCase
{
    private const SOME_REMEMBER_ME_ID = 'some_remember_me_id';
    private const SOME_NAME = 'some name';
    private const SOME_STRING_VALUE = 'some string';
    private const SOME_ANCHOR_JSON_DATA = [
        'type' => 'some type',
        'sub_type' => 'some sub type',
        'value' => 'some anchor value',
        'timestamp' => 1575998454,
    ];

    /**
     * @var \Yoti\Sandbox\Profile\RequestBuilders
     */
    private $requestBuilder;

    public function setup(): void
    {
        $this->requestBuilder = new TokenRequestBuilder();
    }

    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $tokenRequest = $this->requestBuilder->build();

        $this->assertInstanceOf(TokenRequest::class, $tokenRequest);
    }

    /**
     * @covers ::setRememberMeId
     */
    public function testSetRememberMeId()
    {
        $this->requestBuilder->setRememberMeId(self::SOME_REMEMBER_ME_ID);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => self::SOME_REMEMBER_ME_ID,
                'profile_attributes' => []
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * @covers ::setFullName
     * @covers ::setFamilyName
     * @covers ::setGivenNames
     * @covers ::setGender
     * @covers ::setNationality
     * @covers ::setPhoneNumber
     * @covers ::setBase64Selfie
     * @covers ::setEmailAddress
     * @covers ::setPostalAddress
     * @covers ::setStructuredPostalAddress
     * @covers ::createAttribute
     * @covers ::addAttribute
     *
     * @dataProvider stringAttributeSettersDataProvider
     */
    public function testStringAttributeSetters($setterMethod, $name)
    {
        $this->requestBuilder->{$setterMethod}(self::SOME_STRING_VALUE);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => $name,
                        'value' => self::SOME_STRING_VALUE,
                        'derivation' => '',
                        'optional' => false,
                        'anchors' => [],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * @covers ::setFullName
     * @covers ::setFamilyName
     * @covers ::setGivenNames
     * @covers ::setGender
     * @covers ::setNationality
     * @covers ::setPhoneNumber
     * @covers ::setBase64Selfie
     * @covers ::setEmailAddress
     * @covers ::setPostalAddress
     * @covers ::setStructuredPostalAddress
     * @covers ::createAttribute
     * @covers ::addAttribute
     *
     * @dataProvider stringAttributeSettersDataProvider
     */
    public function testStringAttributeSettersWithAnchor($setterMethod, $name)
    {
        $someAnchor = $this->createMock(SandboxAnchor::class);
        $someAnchor->method('jsonSerialize')->willReturn(self::SOME_ANCHOR_JSON_DATA);

        $this->requestBuilder->{$setterMethod}(self::SOME_STRING_VALUE, true, [ $someAnchor ]);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => $name,
                        'value' => self::SOME_STRING_VALUE,
                        'derivation' => '',
                        'optional' => true,
                        'anchors' => [ self::SOME_ANCHOR_JSON_DATA ],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * Provides test data for attribute setters.
     *
     * @return array
     */
    public function stringAttributeSettersDataProvider()
    {
        return [
            ['setFullName', 'full_name'],
            ['setFamilyName', 'family_name'],
            ['setGivenNames', 'given_names'],
            ['setGender', 'gender'],
            ['setNationality', 'nationality'],
            ['setPhoneNumber', 'phone_number'],
            ['setBase64Selfie', 'selfie'],
            ['setEmailAddress', 'email_address'],
            ['setPostalAddress', 'postal_address'],
            ['setStructuredPostalAddress', 'structured_postal_address'],
        ];
    }

    /**
     * @covers ::setDateOfBirth
     */
    public function testSetDateOfBirth()
    {
        $someDOB = new \DateTime();
        $this->requestBuilder->setDateOfBirth($someDOB);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => 'date_of_birth',
                        'value' => $someDOB->format('Y-m-d'),
                        'derivation' => '',
                        'optional' => false,
                        'anchors' => [],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * @covers ::setSelfie
     */
    public function testSetSelfie()
    {
        $this->requestBuilder->setSelfie(self::SOME_STRING_VALUE);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => 'selfie',
                        'value' => base64_encode(self::SOME_STRING_VALUE),
                        'derivation' => '',
                        'optional' => false,
                        'anchors' => [],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * @covers ::setDocumentDetails
     */
    public function testSetDocumentDetails()
    {
        $someDocumentDetails  = $this->createMock(SandboxDocumentDetails::class);
        $someDocumentDetails->method('getValue')->willReturn(self::SOME_STRING_VALUE);

        $this->requestBuilder->setDocumentDetails($someDocumentDetails);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => 'document_details',
                        'value' => self::SOME_STRING_VALUE,
                        'derivation' => '',
                        'optional' => true,
                        'anchors' => [],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }


    /**
     * @covers ::setDocumentDetailsWithString
     */
    public function testSetDocumentDetailsWithString()
    {
        $this->requestBuilder->setDocumentDetailsWithString(self::SOME_STRING_VALUE);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => 'document_details',
                        'value' => self::SOME_STRING_VALUE,
                        'derivation' => '',
                        'optional' => true,
                        'anchors' => [],
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }

    /**
     * @covers ::setAgeVerification
     */
    public function testSetAgeVerification()
    {
        $someAgeVerification  = $this->createMock(SandboxAgeVerification::class);
        $someAgeVerification->method('jsonSerialize')->willReturn([
            'name' => self::SOME_NAME,
            'value' => self::SOME_STRING_VALUE,
        ]);

        $this->requestBuilder->setAgeVerification($someAgeVerification);
        $tokenRequest = $this->requestBuilder->build();

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => null,
                'profile_attributes' => [
                    [
                        'name' => self::SOME_NAME,
                        'value' => self::SOME_STRING_VALUE,
                    ]
                ]
            ]),
            json_encode($tokenRequest)
        );
    }
}
