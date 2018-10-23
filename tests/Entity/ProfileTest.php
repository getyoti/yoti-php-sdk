<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Entity\Profile;
use Yoti\Util\Age\Processor;

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
            ->setMethods(['makeRequest'])
            ->getMock();

        // Stub the method makeRequest to return the result we want
        $this->yotiClient->method('makeRequest')
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

    public function testGetAgeVerifications()
    {
        $profileData = [
            'age_over:18' => new Attribute('age_over:18', 'true', [], []),
            'age_under:18' => new Attribute('age_under:18', 'false', [], []),
        ];
        $processor = new Processor($profileData);
        $resultArr = $processor->getAgeVerificationsFromAttrsMap();
        $ageOver18 = $resultArr['age_over:18'];
        $ageUnder18 = $resultArr['age_under:18'];

        $this->assertTrue($ageOver18->getResult());
        $this->assertEquals(18, $ageOver18->getAge());
        $this->assertEquals('age_over', $ageOver18->getChecktype());
        $this->assertInstanceOf(Attribute::class, $ageOver18->getAttribute());

        $this->assertFalse($ageUnder18->getResult());
        $this->assertEquals(18, $ageUnder18->getAge());
        $this->assertEquals('age_under', $ageUnder18->getChecktype());
        $this->assertInstanceOf(Attribute::class, $ageUnder18->getAttribute());
    }

    public function testShouldReturnFormattedAddressAsPostalAddressWhenNull()
    {
        $structuredPostalAddress = new Attribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $this->dummyStructuredPostalAddress,
            [],
            []
        );
        $prolieData = [
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS => $structuredPostalAddress,
            Profile::ATTR_GIVEN_NAMES => new Attribute(
                Profile::ATTR_GIVEN_NAMES,
                'Given Name TEST',
                [],
                []
            ),
        ];
        $profile = new Profile($prolieData);
        $expectedPostalAddress = '15a North Street CARSHALTON SM5 2HW UK';

        $this->assertEquals('Given Name TEST', $profile->getGivenNames()->getValue());
        $this->assertEquals($expectedPostalAddress, $profile->getPostalAddress()->getValue());
        $this->assertEquals(
            json_encode($this->dummyStructuredPostalAddress),
            json_encode($profile->getStructuredPostalAddress()->getValue())
        );
    }
}