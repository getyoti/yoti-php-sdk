<?php

namespace YotiTest\Media;

use Yoti\Media\Image;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Media\Image
 */
class ImageTest extends TestCase
{
    const SOME_IMAGE_DATA = 'dummyImageData';

    /**
     * @var \Yoti\Media\Image
     */
    public $dummyImage;

    public function setup()
    {
        $this->dummyImage = new Image(self::SOME_IMAGE_DATA, 'png');
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
     * @covers ::__toString
     */
    public function testGetContent()
    {
        $this->assertEquals(self::SOME_IMAGE_DATA, $this->dummyImage->getContent());
        $this->assertEquals(self::SOME_IMAGE_DATA, (string) $this->dummyImage);
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
     * @covers ::imageTypeToMimeType
     * @covers ::validateImageExtension
     *
     * @expectedException \Yoti\Media\Exception\InvalidImageTypeException
     * @expectedExceptionMessage bmp extension not supported
     */
    public function testShouldThrowExceptionForUnsupportedExtension()
    {
        new Image(self::SOME_IMAGE_DATA, 'bmp');
    }
}
