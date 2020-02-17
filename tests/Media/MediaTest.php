<?php

declare(strict_types=1);

namespace Yoti\Test\Media;

use Yoti\Media\Media;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Media\Media
 */
class MediaTest extends TestCase
{
    private const SOME_IMAGE_DATA = 'someMediaData';

    /**
     * @var \Yoti\Media\Media
     */
    public $someMedia;

    public function setup(): void
    {
        $this->someMedia = new Media('image/png', self::SOME_IMAGE_DATA);
    }

    /**
     * @covers ::__construct
     * @covers ::getMimeType
     */
    public function testGetMimeType()
    {
        $this->assertEquals('image/png', $this->someMedia->getMimeType());
    }

    /**
     * @covers ::__construct
     * @covers ::getContent
     * @covers ::__toString
     */
    public function testGetContent()
    {
        $this->assertEquals(self::SOME_IMAGE_DATA, $this->someMedia->getContent());
        $this->assertEquals(self::SOME_IMAGE_DATA, (string) $this->someMedia);
    }

    /**
     * @covers ::__construct
     * @covers ::getBase64Content
     */
    public function testGetBase64Content()
    {
        $expectedValue = 'data:image/png;base64,c29tZU1lZGlhRGF0YQ==';
        $this->assertEquals($expectedValue, $this->someMedia->getBase64Content());
    }
}
