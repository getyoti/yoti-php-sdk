<?php

declare(strict_types=1);

namespace Yoti\Test\Media\Image;

use Yoti\Media\Image;
use Yoti\Media\Image\Png;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Media\Image\Png
 */
class PngTest extends TestCase
{
    private const SOME_IMAGE_DATA = 'somePngData';

    /**
     * @var \Yoti\Media\Image\Png
     */
    private $someImage;

    public function setup(): void
    {
        $this->someImage = new Png(self::SOME_IMAGE_DATA);
    }

    /**
     * @covers ::__construct
     * @covers ::getMimeType
     */
    public function testGetMimeType()
    {
        $this->assertEquals('image/png', $this->someImage->getMimeType());
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
