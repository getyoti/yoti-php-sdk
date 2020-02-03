<?php

declare(strict_types=1);

namespace Yoti\Test\Media;

use Yoti\Media\Image;
use Yoti\Media\Media;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Media\Image
 */
class ImageTest extends TestCase
{
    public function testShouldBeInstanceOfMedia()
    {
        $image = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf(Media::class, $image);
    }
}
