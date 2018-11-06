<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;

class ProfileTest extends TestCase
{
    /**
     * @var \Yoti\Entity\Profile
     */
    public $profile;
    /**
     * @var \Yoti\YotiClient
     */
    public $yotiClient;
    /**
     * @var string
     */
    public $expectedPhoneNumber;

    public $dummyStructuredPostalAddress;

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

    public function testGetAttributeValue()
    {
        $phoneNumber = $this->profile->getPhoneNumber();
        $this->assertEquals($this->expectedPhoneNumber, $phoneNumber->getValue());
    }

    public function testGetAttributeName()
    {
        $phoneNumber = $this->profile->getPhoneNumber();
        $this->assertEquals('phone_number', $phoneNumber->getName());
    }

    public function testShouldReturnFormattedAddressAsPostalAddressWhenNull()
    {
        $structuredPostalAddress = new Attribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $this->dummyStructuredPostalAddress,
            [],
            []
        );
        $profileData = [
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS => $structuredPostalAddress,
            Profile::ATTR_GIVEN_NAMES => new Attribute(
                Profile::ATTR_GIVEN_NAMES,
                'Given Name TEST',
                [],
                []
            ),
        ];
        $profile = new Profile($profileData);
        $expectedPostalAddress = '15a North Street CARSHALTON SM5 2HW UK';

        $this->assertEquals('Given Name TEST', $profile->getGivenNames()->getValue());
        $this->assertEquals($expectedPostalAddress, $profile->getPostalAddress()->getValue());
        $this->assertEquals(
            json_encode($this->dummyStructuredPostalAddress),
            json_encode($profile->getStructuredPostalAddress()->getValue())
        );
    }

    /**
     * Should not return age_verifications in the array
     */
    public function testGetAttributes()
    {
        $profileData = $this->getDummyProfileDataWithAgeVerifications();
        $profile = new Profile($profileData);

        $this->assertArrayNotHasKey(Profile::ATTR_AGE_VERIFICATIONS, $profile->getAttributes());
    }

    public function testFindAgeOverVerification()
    {
        $profileData = $this->getDummyProfileDataWithAgeVerifications();
        $profile = new Profile($profileData);
        $ageOver35 = $profile->findAgeOverVerification(35);

        $this->assertInstanceOf(AgeVerification::class, $ageOver35);
        $this->assertEquals('age_over', $ageOver35->getCheckType());
        $this->assertEquals(35, $ageOver35->getAge());
        $this->assertTrue($ageOver35->getResult());
    }

    public function testFindAgeUnderVerification()
    {
        $profileData = $this->getDummyProfileDataWithAgeVerifications();
        $profile = new Profile($profileData);
        $ageUnder18 = $profile->findAgeUnderVerification(18);

        $this->assertInstanceOf(AgeVerification::class, $ageUnder18);
        $this->assertEquals('age_under', $ageUnder18->getCheckType());
        $this->assertEquals(18, $ageUnder18->getAge());
        $this->assertFalse($ageUnder18->getResult());
    }

    public function getDummyProfileDataWithAgeVerifications()
    {
        $profileData = [
            Profile::ATTR_AGE_VERIFICATIONS => [
                'age_under:18' => new AgeVerification(
                    new Attribute(
                        'age_under:18',
                        'false',
                        [],
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
                        [],
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
                [],
                []
            ),
            Profile::ATTR_FAMILY_NAME => new Attribute(
                Profile::ATTR_FAMILY_NAME,
                'TEST FAMILY NAME',
                [],
                []
            ),
        ];
        return $profileData;
    }
}