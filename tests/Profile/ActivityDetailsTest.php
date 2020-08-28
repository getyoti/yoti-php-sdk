<?php

declare(strict_types=1);

namespace Yoti\Test\Profile;

use Yoti\Media\Image;
use Yoti\Profile\ActivityDetails;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\Attribute;
use Yoti\Profile\ExtraData;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Profile\Receipt;
use Yoti\Profile\UserProfile;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Profile\ActivityDetails
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

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var array Receipt array.
     */
    private $receiptArr;

    public function setup(): void
    {
        $this->pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $this->receiptArr = json_decode(file_get_contents(TestData::RECEIPT_JSON), true)['receipt'];
        $this->activityDetails = new ActivityDetails(
            new Receipt($this->receiptArr),
            $this->pemFile
        );
    }

    /**
     * Test creation of ActivityDetails with test data.
     *
     * @covers ::__construct
     */
    public function testActivityDetailsInstance()
    {
        $this->assertInstanceOf(ActivityDetails::class, $this->activityDetails);

        $profile = $this->activityDetails->getProfile();
        $this->assertNull($profile->getFamilyName());
        $this->assertNull($profile->getFullName());
        $this->assertNull($profile->getDateOfBirth());
        $this->assertNull($profile->getEmailAddress());
        $this->assertEquals('+447474747474', $profile->getPhoneNumber()->getValue());
        $this->assertInstanceOf(Attribute::class, $profile->getSelfie());
        $this->assertInstanceOf(Image::class, $profile->getSelfie()->getValue());
    }

    /**
     * @covers ::getRememberMeId
     * @covers ::setRememberMeId
     */
    public function testGetRememberMeId()
    {
        $rememberMeId = 'Hig2yAT79cWvseSuXcIuCLa5lNkAPy70rxetUaeHlTJGmiwc/g1MWdYWYrexWvPU';
        $this->assertEquals($rememberMeId, $this->activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     * @covers ::setRememberMeId
     */
    public function testGetRememberMeIdNotPresent()
    {
        // Remove Remember Me ID from test receipt.
        unset($this->receiptArr['remember_me_id']);
        $receipt = new Receipt($this->receiptArr);

        $activityDetails = new ActivityDetails($receipt, $this->pemFile);
        $this->assertNull($activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getRememberMeId
     * @covers ::setRememberMeId
     */
    public function testGetRememberMeIdEmpty()
    {
        // Set Remember Me ID to empty string.
        $this->receiptArr['remember_me_id'] = '';
        $receipt = new Receipt($this->receiptArr);

        $activityDetails = new ActivityDetails($receipt, $this->pemFile);
        $this->assertEquals('', $activityDetails->getRememberMeId());
    }

    /**
     * @covers ::getParentRememberMeId
     * @covers ::setParentRememberMeId
     */
    public function testGetParentRememberMeIdExists()
    {
        $parentRememberMeId = 'f5RjVQMyoKOvO/hkv43Ik+t6d6mGfP2tdrNijH4k4qafTG0FSNUgQIvd2Z3Nx1j8';
        $this->assertEquals($parentRememberMeId, $this->activityDetails->getParentRememberMeId());
    }

    /**
     * @covers ::getProfile
     * @covers ::setProfile
     */
    public function testGetProfile()
    {
        $this->assertInstanceOf(UserProfile::class, $this->activityDetails->getProfile());
    }

    /**
     * @covers ::getApplicationProfile
     * @covers ::setApplicationProfile
     */
    public function testGetApplicationProfile()
    {
        $this->assertInstanceOf(
            ApplicationProfile::class,
            $this->activityDetails->getApplicationProfile()
        );
    }

    /**
     * @covers ::getTimestamp
     * @covers ::setTimestamp
     */
    public function testGetTimestamp()
    {
        $timestamp = $this->activityDetails->getTimestamp();

        $timestamp->setTimezone(new \DateTimeZone('UTC'));
        $this->assertEquals('19-07-2016 08:55:38', $timestamp->format('d-m-Y H:i:s'));
        $this->assertEquals(1468918538, $timestamp->getTimestamp());
    }

    /**
     * @covers ::setTimestamp
     * @covers ::getTimestamp
     */
    public function testMissingTimestamp()
    {
        $this->captureExpectedLogs();

        $this->receiptArr['timestamp'] = 'some-invalid-time';
        $receipt = new Receipt($this->receiptArr);
        $activityDetails = new ActivityDetails($receipt, $this->pemFile);

        $this->assertNull($activityDetails->getTimestamp());
        $this->assertLogContains('warning: Could not parse string to DateTime');
    }

    /**
     * @covers ::getReceiptId
     */
    public function testGetReceipt()
    {
        $receiptId = '9HNJDX5bEIN5TqBm0OGzVIc1LaAmbzfx6eIrwNdwpHvKeQmgPujyogC+r7hJCVPl';
        $this->assertEquals($receiptId, $this->activityDetails->getReceiptId());
    }

    /**
     * @covers ::getExtraData
     * @covers ::setExtraData
     */
    public function testGetExtraData()
    {
        $extraData = $this->activityDetails->getExtraData();

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertInstanceOf(AttributeIssuanceDetails::class, $extraData->getAttributeIssuanceDetails());
    }
}
