<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Entity\Image;
use Yoti\Util\Profile\AttributeConverter;

class AttributeConverterTest extends TestCase
{
    public function testDateTypeShouldReturnDateTime()
    {
        $dateTime = AttributeConverter::convertValueBasedOnContentType(
            '1980/12/01',
            AttributeConverter::CONTENT_TYPE_DATE
        );
        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertEquals('01-12-1980', $dateTime->format('d-m-Y'));
    }

    public function testImageTypeShouldReturnImageObject()
    {
        $image = AttributeConverter::convertValueBasedOnContentType('dummyImageData',
            AttributeConverter::CONTENT_TYPE_JPEG
        );
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals('dummyImageData', $image->getContent());
    }
}