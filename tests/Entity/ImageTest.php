<?php
namespace YotiTest\Entity;

use Yoti\Entity\Image;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Entity\Image
 */
class ImageTest extends TestCase
{
    /**
     * @var \Yoti\Entity\Image
     */
    public $dummyImage;

    public function setup()
    {
        $this->dummyImage = new Image('dummyImageData', 'png');
    }

    /**
     * @covers ::getMimeType
     */
    public function testGetMimeType()
    {
        $this->assertEquals('image/png', $this->dummyImage->getMimeType());
    }

    /**
     * @covers ::getContent
     */
    public function testGetContent()
    {
        $this->assertEquals('dummyImageData', $this->dummyImage->getContent());
    }

    /**
     * @covers ::getBase64Content
     */
    public function testGetBase64Content()
    {
        $expectedValue = 'data:image/png;base64,ZHVtbXlJbWFnZURhdGE=';
        $this->assertEquals($expectedValue, $this->dummyImage->getBase64Content());
    }

    /**
     * @covers ::__construct
     */
    public function testShouldThrowExceptionForUnsupportedExtension()
    {
        $this->expectException("\Yoti\Exception\AttributeException");
        $image = new Image('dummyImageData', 'bmp');
    }
}