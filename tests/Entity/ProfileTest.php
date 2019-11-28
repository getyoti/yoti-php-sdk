<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;
use Yoti\Entity\Anchor;
use Yoti\Util\Profile\AnchorListConverter;
use YotiTest\Util\Profile\TestAnchors;

/**
 * @coversDefaultClass \Yoti\Entity\Profile
 */
class ProfileTest extends TestCase
{
    /**
     * @covers ::getGivenNames
     * @covers ::getFamilyName
     * @covers ::getGivenNames
     * @covers ::getFullName
     * @covers ::getDateOfBirth
     * @covers ::getAgeVerifications
     * @covers ::getGender
     * @covers ::getNationality
     * @covers ::getPhoneNumber
     * @covers ::getSelfie
     * @covers ::getEmailAddress
     * @covers ::getPostalAddress
     * @covers ::getDocumentDetails
     * @covers ::getDocumentImages
     * @covers ::getStructuredPostalAddress
     *
     * @dataProvider attributeGettersProvider
     */
    public function testAttributeGetters($name, $method)
    {
        $expectedValue = 'some value';
        $profile = new Profile([
            $name => new Attribute($name, $expectedValue, []),
        ]);
        $attribute = $profile->{$method}();
        $this->assertEquals($expectedValue, $attribute->getValue());
        $this->assertEquals($name, $attribute->getName());
    }

    /**
     * Provides mapping of attribute names and corresponding getters.
     *
     * @return array
     */
    public function attributeGettersProvider()
    {
        return [
            [ 'family_name', 'getFamilyName' ],
            [ 'given_names' , 'getGivenNames' ],
            [ 'full_name', 'getFullName' ],
            [ 'date_of_birth', 'getDateOfBirth' ],
            [ 'age_verifications', 'getAgeVerifications' ],
            [ 'gender', 'getGender' ],
            [ 'nationality', 'getNationality' ],
            [ 'phone_number', 'getPhoneNumber' ],
            [ 'selfie', 'getSelfie' ],
            [ 'email_address', 'getEmailAddress' ],
            [ 'postal_address', 'getPostalAddress' ],
            [ 'document_details', 'getDocumentDetails' ],
            [ 'document_images', 'getDocumentImages' ],
            [ 'structured_postal_address', 'getStructuredPostalAddress' ],
        ];
    }

    /**
     * @covers ::getPostalAddress
     * @covers ::getFormattedAddress
     * @covers ::getAttributeAnchorMap
     * @covers ::getGivenNames
     * @covers ::getStructuredPostalAddress
     */
    public function testShouldReturnFormattedAddressAsPostalAddressWhenNull()
    {
        $expectedPostalAddress = [
            "address_format" => 1,
            "building_number" => "15a",
            "address_line1" => "15a North Street",
            "town_city" => "CARSHALTON",
            "postal_code" => "SM5 2HW",
            "country_iso" => "GBR",
            "country" => "UK",
            "formatted_address" => "15a North Street CARSHALTON SM5 2HW UK"
        ];

        $anchorsMap = AnchorListConverter::convert(new \ArrayObject([
            $this->parseAnchor(TestAnchors::UNKNOWN_ANCHOR),
            $this->parseAnchor(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR),
            $this->parseAnchor(TestAnchors::SOURCE_DL_ANCHOR),
        ]));

        $structuredPostalAddress = new Attribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $expectedPostalAddress,
            $anchorsMap
        );

        $profile = new Profile([
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS => $structuredPostalAddress,
        ]);
        $this->assertEquals(
            json_encode($expectedPostalAddress),
            json_encode($profile->getStructuredPostalAddress()->getValue())
        );

        $postalAddress = $profile->getPostalAddress();

        $this->assertEquals('15a North Street CARSHALTON SM5 2HW UK', $postalAddress->getValue());
        $this->assertEquals($anchorsMap[Anchor::TYPE_SOURCE_OID], $postalAddress->getSources());
        $this->assertEquals($anchorsMap[Anchor::TYPE_VERIFIER_OID], $postalAddress->getVerifiers());

        $anchors = [];
        array_walk($anchorsMap, function ($val) use (&$anchors) {
            $anchors = array_merge($anchors, array_values($val));
        });
        $this->assertEquals($anchors, $postalAddress->getAnchors());
    }

    /**
     * Should not return age_verifications in the array
     *
     * @covers ::getAttributes
     *
     * @dataProvider getDummyProfileDataWithAgeVerifications
     */
    public function testGetAttributes($profileData)
    {
        $profile = new Profile($profileData);

        $this->assertArrayNotHasKey(Profile::ATTR_AGE_VERIFICATIONS, $profile->getAttributes());
    }

    /**
     * @covers ::findAgeOverVerification
     * @covers ::getAgeVerificationByAttribute
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     * @covers \Yoti\Entity\AgeVerification::getResult
     *
     * @dataProvider getDummyProfileDataWithAgeVerifications
     */
    public function testFindAgeOverVerification($profileData)
    {
        $profile = new Profile($profileData);
        $ageOver35 = $profile->findAgeOverVerification(35);

        $this->assertInstanceOf(AgeVerification::class, $ageOver35);
        $this->assertEquals('age_over', $ageOver35->getCheckType());
        $this->assertEquals(35, $ageOver35->getAge());
        $this->assertTrue($ageOver35->getResult());
    }

    /**
     * @covers ::findAgeUnderVerification
     * @covers ::getAgeVerificationByAttribute
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     * @covers \Yoti\Entity\AgeVerification::getResult
     *
     * @dataProvider getDummyProfileDataWithAgeVerifications
     */
    public function testFindAgeUnderVerification($profileData)
    {
        $profile = new Profile($profileData);
        $ageUnder18 = $profile->findAgeUnderVerification(18);

        $this->assertInstanceOf(AgeVerification::class, $ageUnder18);
        $this->assertEquals('age_under', $ageUnder18->getCheckType());
        $this->assertEquals(18, $ageUnder18->getAge());
        $this->assertFalse($ageUnder18->getResult());
    }

    /**
     * Profile data provider with age verifications.
     */
    public function getDummyProfileDataWithAgeVerifications()
    {
        $profileData = [
            Profile::ATTR_AGE_VERIFICATIONS => [
                'age_under:18' => new AgeVerification(
                    new Attribute(
                        'age_under:18',
                        'false',
                        []
                    ),
                    'age_under',
                    18,
                    false
                ),
                'age_over:35' => new AgeVerification(
                    new Attribute(
                        'age_over:35',
                        'true',
                        []
                    ),
                    'age_over',
                    35,
                    true
                ),
            ],
            Profile::ATTR_GIVEN_NAMES => new Attribute(
                Profile::ATTR_GIVEN_NAMES,
                'TEST GIVEN NAMES',
                []
            ),
            Profile::ATTR_FAMILY_NAME => new Attribute(
                Profile::ATTR_FAMILY_NAME,
                'TEST FAMILY NAME',
                []
            ),
        ];
        return [[$profileData]];
    }

    /**
     * @covers ::getDocumentImages
     */
    public function testGetDocumentImages()
    {
        $mockAttr = $this->getMockBuilder(\Yoti\Entity\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $profileData = [
            'document_images' => $mockAttr,
        ];
        $profile = new Profile($profileData);
        $this->assertSame($profileData['document_images'], $profile->getDocumentImages());
    }

    /**
     * @param string $anchorString
     *
     * @return array $anchors
     */
    private function parseAnchor($anchorString)
    {
        $anchor = new \Yoti\Protobuf\Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}
