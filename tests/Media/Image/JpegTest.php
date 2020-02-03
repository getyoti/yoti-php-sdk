<?php

declare(strict_types=1);

namespace Yoti\Test\Media\Image;

use Yoti\Media\Image;
use Yoti\Media\Image\Jpeg;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Media\Image\Jpeg
 */
class JpegTest extends TestCase
{
    private const SOME_IMAGE_DATA = 'someJpegData';

    /**
     * @var \Yoti\Media\Image\Jpeg
     */
    private $someImage;

    public function setup(): void
    {
        $this->someImage = new Jpeg(self::SOME_IMAGE_DATA);
    }

    /**
     * @covers ::__construct
     * @covers ::getMimeType
     */
    public function testGetMimeType()
    {
        $this->assertEquals('image/jpeg', $this->someImage->getMimeType());
    }

    /**
     * @covers ::__construct
     * @covers ::getContent
     */
    public function testGetContent()
    {
        $this->assertEquals(self::SOME_IMAGE_DATA, $this->someImage->getContent());
    }

    public function testShouldBeInstanceOfImage()
    {
        $this->assertInstanceOf(Image::class, $this->someImage);
    }
}
