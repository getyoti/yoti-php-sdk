<?php
namespace YotiTest\Entity;

use Yoti\Entity\Image;
use YotiTest\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    public $dummyImage;

    public function setup()
    {
        $this->dummyImage = new Image('dummyImageData', 'png');
    }

    public function testGetMimeType()
    {
        $this->assertEquals('image/png', $this->dummyImage->getMimeType());
    }

    public function testGetContent()
    {
        $this->assertEquals('dummyImageData', $this->dummyImage->getContent());
    }

    public function testGetBase64Content()
    {
        $expectedValue = 'data:image/png;base64,ZHVtbXlJbWFnZURhdGE=';
        $this->assertEquals($expectedValue, $this->dummyImage->getBase64Content());
    }

    public function testShouldThrowExceptionForUnsupportedExtension()
    {
        $this->expectException("\Yoti\Exception\AttributeException");
        $image = new Image('dummyImageData', 'bmp');
    }
}