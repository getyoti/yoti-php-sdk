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
     * @var \Yoti\Entity\Profile
     */
    private $profile;

    /**
     * @var \Yoti\YotiClient
     */
    private $yotiClient;

    /**
     * @var string
     */
    private $expectedPhoneNumber;

    /**
     * @var Array Structured Postal Address
     */
    private $dummyStructuredPostalAddress;

    public function setup()
    {
        $this->pem = file_get_contents(PEM_FILE);
        $this->expectedPhoneNumber = '+447474747474';
        $result['response'] = file_get_contents(RECEIPT_JSON);
        $result['http_code'] = 200;

        $this->dummyStructuredPostalAddress = [
            "address_format" => 1,
            "building_number" => "15a",
            "address_line1" => "15a North Street",
            "town_city" => "CARSHALTON",
            "postal_code" => "SM5 2HW",
            "country_iso" => "GBR",
            "country" => "UK",
            "formatted_address" => "15a North Street CARSHALTON SM5 2HW UK"
        ];

        $this->yotiClient = $this->getMockBuilder('Yoti\YotiClient')
            ->setConstructorArgs([SDK_ID, $this->pem])
            ->setMethods(['sendRequest'])
            ->getMock();

        // Stub the method makeRequest to return the result we want
        $this->yotiClient->method('sendRequest')
            ->willReturn($result);

        $activityDetails = $this->yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);
        $this->profile = $activityDetails->getProfile();
    }

    /**
     * @covers \Yoti\Entity\Attribute::getValue
     * @covers ::getPhoneNumber
     * @covers ::getProfileAttribute
     */
    public function testGetAttributeValue()
    {
        $phoneNumber = $this->profile->getPhoneNumber();
        $this->assertInstanceOf(Attribute::class, $phoneNumber);
        $this->assertEquals($this->expectedPhoneNumber, $phoneNumber->getValue());
    }

    /**
     * @covers \Yoti\Entity\Attribute::getName
     * @covers ::getPhoneNumber
     * @covers ::getProfileAttribute
     */
    public function testGetAttributeName()
    {
        $phoneNumber = $this->profile->getPhoneNumber();
        $this->assertInstanceOf(Attribute::class, $phoneNumber);
        $this->assertEquals('phone_number', $phoneNumber->getName());
    }

    /**
     * @covers ::getPostalAddress
     * @covers ::getStructuredPostalAddress
     */
    public function testShouldReturnFormattedAddressAsPostalAddressWhenNull()
    {
        $anchorsMap = AnchorListConverter::convert(new \ArrayObject([
            $this->parseAnchor(TestAnchors::UNKNOWN_ANCHOR),
            $this->parseAnchor(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR),
            $this->parseAnchor(TestAnchors::SOURCE_DL_ANCHOR),
        ]));

        $structuredPostalAddress = new Attribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $this->dummyStructuredPostalAddress,
            $anchorsMap
        );

        $profile = new Profile([
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS => $structuredPostalAddress,
            Profile::ATTR_GIVEN_NAMES => new Attribute(
                Profile::ATTR_GIVEN_NAMES,
                'Given Name TEST',
                []
            ),
        ]);
        $this->assertEquals('Given Name TEST', $profile->getGivenNames()->getValue());
        $this->assertEquals(
            json_encode($this->dummyStructuredPostalAddress),
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
     * @dataProvider getDummyProfileDataWithAgeVerifications
     */
    public function testGetAttributes($profileData)
    {
        $profile = new Profile($profileData);

        $this->assertArrayNotHasKey(Profile::ATTR_AGE_VERIFICATIONS, $profile->getAttributes());
    }

    /**
     * @covers ::findAgeOverVerification
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     * @covers \Yoti\Entity\AgeVerification::getResult
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
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     * @covers \Yoti\Entity\AgeVerification::getResult
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
        $anchor = new \Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}
