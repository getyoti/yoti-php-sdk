<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Entity\Image;
use Yoti\Entity\Receipt;
use Yoti\ActivityDetails;
use Yoti\Util\Profile\AttributeConverter;

class AttributeConverterTest extends TestCase
{
    public function testDateTypeShouldReturnDateTime()
    {
        $dateTime = AttributeConverter::convertTimestampToDate('1980/12/01');
        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertEquals('01-12-1980', $dateTime->format('d-m-Y'));
    }

    public function testSelfieValueShouldReturnImageObject()
    {
        $pem = file_get_contents(PEM_FILE);
        $receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true);
        $receipt = new Receipt($receiptArr['receipt']);

        $this->activityDetails = new ActivityDetails($receipt, $pem);
        $this->profile = $this->activityDetails->getProfile();
        $this->assertInstanceOf(Image::class, $this->profile->getSelfie()->getValue());
    }
}