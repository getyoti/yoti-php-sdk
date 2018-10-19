<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
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

    public function setup()
    {
        $this->pem = file_get_contents(PEM_FILE);
        $this->expectedPhoneNumber = '+447474747474';
        $result['response'] = file_get_contents(RECEIPT_JSON);
        $result['http_code'] = 200;

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
        $resultArr = $processor->findAgeVerifications();
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
}