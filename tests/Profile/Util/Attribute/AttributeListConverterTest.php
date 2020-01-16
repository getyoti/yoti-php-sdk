<?php

declare(strict_types=1);

namespace YotiTest\Profile\Util\Attribute;

use Yoti\Profile\Util\Attribute\AttributeConverter;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\Attribute;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AttributeListConverter
 */
class AttributeListConverterTest extends TestCase
{
    /**
     * @covers ::convertToYotiAttributesMap
     */
    public function testConvertToYotiAttributesMap()
    {
        $someName = 'some name';
        $someValue = 'some value';

        $someAttribute = $this->createMock(Attribute::class);
        $someAttribute
            ->method('getName')
            ->willReturn($someName);
        $someAttribute
            ->method('getValue')
            ->willReturn($someValue);
        $someAttribute
            ->method('getContentType')
            ->willReturn(AttributeConverter::CONTENT_TYPE_STRING);
        $someAttribute
            ->method('getAnchors')
            ->willReturn($this->createMock(\Traversable::class));

        $someAttributeList = $this->createMock(AttributeList::class);
        $someAttributeList
            ->method('getAttributes')
            ->willReturn([
                $someAttribute,
                $this->createMock(Attribute::class),
            ]);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesMap($someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertEquals($someValue, $yotiAttributesList[$someName]->getValue());
    }
}
