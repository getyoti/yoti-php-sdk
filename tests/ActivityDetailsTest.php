<?php

namespace YotiTest;

use Yoti\ActivityDetails;
use Yoti\Entity\Attribute;
use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Yoti\Entity\Image;
use Yoti\Entity\ApplicationProfile;

class ActivityDetailsTest extends TestCase
{
    /**
     * @var ActivityDetails
     */
    public $activityDetails;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var ApplicationProfile
     */
    public $applicationProfile;

    public $rememberMeId = 'Hig2yAT79cWvseSuXcIuCLa5lNkAPy70rxetUaeHlTJGmiwc/g1MWdYWYrexWvPU';

    public function setUp()
    {
        $pem = file_get_contents(PEM_FILE);
        $receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true);
        $receipt = new Receipt($receiptArr['receipt']);

        $this->activityDetails = new ActivityDetails($receipt, $pem);
        $this->profile = $this->activityDetails->getProfile();
        $this->applicationProfile = $this->activityDetails->getApplicationProfile();
    }

    /**
     * Test getting ActivityDetails Instance
     */
    public function testActivityDetailsInstance()
    {
        $this->assertInstanceOf(ActivityDetails::class, $this->activityDetails);
    }

    /**
     * Test getting RememberMeId
     */
    public function testGetRememberMeId()
    {
        $this->assertEquals($this->rememberMeId, $this->activityDetails->getRememberMeId());
    }

    /**
     * Test getting Given Names
     */
    public function testGetProfile()
    {
        $this->assertInstanceOf(Profile::class, $this->activityDetails->getProfile());
    }

    public function testGetApplicationProfile()
    {
        $this->assertInstanceOf(
            ApplicationProfile::class,
            $this->activityDetails->getApplicationProfile()
        );
    }

    /**
     * Test getting Family Name
     */
    public function testGetFamilyName()
    {
        $this->assertNull($this->profile->getFamilyName());
    }

    /**
     * Test getting Full Name
     */
    public function testGetFullName()
    {
        $this->assertNull($this->profile->getFullName());
    }

    /**
     * Test getting Date Of Birth
     */
    public function testGetDateOfBirth()
    {
        $this->assertNull($this->profile->getDateOfBirth());
    }

    /**
     * Test getting Phone Number
     */
    public function testGetPhoneNumber()
    {
        $this->assertEquals('+447474747474', $this->profile->getPhoneNumber()->getValue());
    }

    /**
     * Test getting Email Address
     */
    public function testGetEmailAddress()
    {
        $this->assertNull($this->profile->getEmailAddress());
    }

    /**
     * Test getting Selfie
     */
    public function testGetSelfie()
    {
        $this->assertInstanceOf(Attribute::class, $this->profile->getSelfie());
        $this->assertInstanceOf(Image::class, $this->profile->getSelfie()->getValue());
    }

    public function testGetTimestamp()
    {
        $timestamp = $this->activityDetails->getTimestamp();
        $this->assertEquals('19-07-2016 08:55:38', $timestamp->format('d-m-Y H:i:s'));
    }

    public function testGetReceipt()
    {
        $receiptId = '9HNJDX5bEIN5TqBm0OGzVIc1LaAmbzfx6eIrwNdwpHvKeQmgPujyogC+r7hJCVPl';
        $this->assertEquals($receiptId, $this->activityDetails->getReceiptId());
    }
}