<?php

namespace YotiTest\Entity;

use Yoti\Entity\Attribute;
use Yoti\Entity\Image;
use YotiTest\TestCase;
use Yoti\Entity\ApplicationProfile;

/**
 * @coversDefaultClass \Yoti\Entity\ApplicationProfile
 */
class ApplicationProfileTest extends TestCase
{
    /**
     * @var ApplicationProfile
     */
    private $dummyProfile;

    public function setup()
    {
        $dummyData = [
            'application_name' => new Attribute('application_name', 'Test PHP SDK', []),
            'application_url' => new Attribute('application_url', 'https://localhost', []),
            'application_receipt_bgcolor' => new Attribute('application_receipt_bgcolor', '#F5F5F5', []),
            'application_logo' => new Attribute('application_logo', new Image('dummyImageData', 'png'), []),
        ];
        $this->dummyProfile = new ApplicationProfile($dummyData);
    }

    /**
     * @covers ::getApplicationName
     */
    public function testGetApplicationName()
    {
        $this->assertEquals(
            'Test PHP SDK',
            $this->dummyProfile->getApplicationName()->getValue()
        );
    }

    /**
     * @covers ::getApplicationUrl
     */
    public function testGetApplicationUrl()
    {
        $this->assertEquals(
            'https://localhost',
            $this->dummyProfile->getApplicationUrl()->getValue()
        );
    }

    /**
     * @covers ::getApplicationReceiptBgColor
     */
    public function testGetApplicationReceiptBgColor()
    {
        $this->assertEquals(
            '#F5F5F5',
            $this->dummyProfile->getApplicationReceiptBgColor()->getValue()
        );
    }

    /**
     * @covers ::getApplicationLogo
     * @covers \Yoti\Entity\Image::getContent
     */
    public function testGetApplicationLogoImageData()
    {
        $this->assertEquals(
            'dummyImageData',
            $this->dummyProfile->getApplicationLogo()->getValue()->getContent()
        );
    }

    /**
     * @covers ::getApplicationLogo
     * @covers \Yoti\Entity\Image::getMimeType
     */
    public function testGetApplicationLogoImageType()
    {
        $this->assertEquals(
            'image/png',
            $this->dummyProfile->getApplicationLogo()->getValue()->getMimeType()
        );
    }
}
