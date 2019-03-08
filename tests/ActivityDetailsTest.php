<?php

namespace YotiTest;

use Yoti\ActivityDetails;
use Yoti\Entity\Attribute;
use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Yoti\Entity\Image;
use Yoti\Entity\ApplicationProfile;

/**
 * @coversDefaultClass \Yoti\ActivityDetails
 */
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

    public function setUp()
    {
        $this->activityDetails = new ActivityDetails(
            new Receipt($this->getReceiptArray()),
            $this->getPem()
        );
        $this->profile = $this->activityDetails->getProfile();
        $this->applicationProfile = $this->activityDetails->getApplicationProfile();
    }

    /**
     * Get pem file contents.
     *
     * @return string
     */
    private function getPem()
    {
        return file_get_contents(PEM_FILE);
    }

    /**
     * Get test receipt.
     *
     * @return array
     */
    private function getReceiptArray()
    {
        return json_decode(file_get_contents(RECEIPT_JSON), true)['receipt'];
    }

    /**
     * Test getting ActivityDetails Instance.
     */
    public function testActivityDetailsInstance()
    {
        $this->assertInstanceOf(ActivityDetails::class, $this->activityDetails);
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeId()
    {
        $rememberMeId = 'Hig2yAT79cWvseSuXcIuCLa5lNkAPy70rxetUaeHlTJGmiwc/g1MWdYWYrexWvPU';
        $this->assertEquals($rememberMeId, $this->activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeIdNotPresent()
    {
        // Remove Remember Me ID from test receipt.
        $receiptArr = $this->getReceiptArray();
        unset($receiptArr['remember_me_id']);
        $receipt = new Receipt($receiptArr);

        $activityDetails = new ActivityDetails($receipt, $this->getPem());
        $this->assertNull($activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     */
    public function testGetRememberMeIdEmpty()
    {
        // Set Remember Me ID to empty string.
        $receiptArr = $this->getReceiptArray();
        $receiptArr['remember_me_id'] = '';
        $receipt = new Receipt($receiptArr);

        $activityDetails = new ActivityDetails($receipt, $this->getPem());
        $this->assertEquals('', $activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getParentRememberMeId
     */
    public function testGetParentRememberMeIdExists()
    {
        $parentRememberMeId = 'f5RjVQMyoKOvO/hkv43Ik+t6d6mGfP2tdrNijH4k4qafTG0FSNUgQIvd2Z3Nx1j8';
        $this->assertEquals($parentRememberMeId, $this->activityDetails->getParentRememberMeId());
    }

    /**
     * @covers ::getProfile
     */
    public function testGetProfile()
    {
        $this->assertInstanceOf(Profile::class, $this->activityDetails->getProfile());
    }

    /**
     * @covers ::getApplicationProfile
     */
    public function testGetApplicationProfile()
    {
        $this->assertInstanceOf(
            ApplicationProfile::class,
            $this->activityDetails->getApplicationProfile()
        );
    }

    /**
     * @covers \Yoti\Entity\Profile::getFamilyName
     */
    public function testGetFamilyName()
    {
        $this->assertNull($this->profile->getFamilyName());
    }

    /**
     * @covers \Yoti\Entity\Profile::getFullName
     */
    public function testGetFullName()
    {
        $this->assertNull($this->profile->getFullName());
    }

    /**
     * @covers \Yoti\Entity\Profile::getDateOfBirth
     */
    public function testGetDateOfBirth()
    {
        $this->assertNull($this->profile->getDateOfBirth());
    }

    /**
     * @covers \Yoti\Entity\Profile::getPhoneNumber
     * @covers \Yoti\Entity\Attribute::getValue
     */
    public function testGetPhoneNumber()
    {
        $this->assertEquals('+447474747474', $this->profile->getPhoneNumber()->getValue());
    }

    /**
     * @covers \Yoti\Entity\Profile::getEmailAddress
     */
    public function testGetEmailAddress()
    {
        $this->assertNull($this->profile->getEmailAddress());
    }

    /**
     * @covers \Yoti\Entity\Profile::getSelfie
     * @covers \Yoti\Entity\Attribute::getValue
     */
    public function testGetSelfie()
    {
        $this->assertInstanceOf(Attribute::class, $this->profile->getSelfie());
        $this->assertInstanceOf(Image::class, $this->profile->getSelfie()->getValue());
    }

    /**
     * ::getTimestamp
     */
    public function testGetTimestamp()
    {
        $timestamp = $this->activityDetails->getTimestamp();
        $this->assertEquals('19-07-2016 08:55:38', $timestamp->format('d-m-Y H:i:s'));
    }

    /**
     * ::getReceiptId
     */
    public function testGetReceipt()
    {
        $receiptId = '9HNJDX5bEIN5TqBm0OGzVIc1LaAmbzfx6eIrwNdwpHvKeQmgPujyogC+r7hJCVPl';
        $this->assertEquals($receiptId, $this->activityDetails->getReceiptId());
    }
}
