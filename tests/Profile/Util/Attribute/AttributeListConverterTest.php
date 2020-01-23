<?php

declare(strict_types=1);

namespace YotiTest\Profile\Util\Attribute;

use Yoti\Profile\Attribute;
use Yoti\Profile\Util\Attribute\AttributeConverter;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\Attribute as AttributeProto;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AttributeListConverter
 */
class AttributeListConverterTest extends TestCase
{
    /**
     * @covers ::convertToYotiAttributesList
     */
    public function testConvertToYotiAttributesList()
    {
        $someName = 'some name';
        $someValue = 'some value';

        $someAttribute = $this->createMock(AttributeProto::class);
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

        $someNullNameAttribute = $this->createMock(AttributeProto::class);
        $someNullNameAttribute
            ->method('getName')
            ->willReturn(null);

        $someUnknownAttribute = $this->createMock(AttributeProto::class);
        $someUnknownAttribute
            ->method('getName')
            ->willReturn('');
        $someUnknownAttribute
            ->method('getValue')
            ->willReturn('');
        $someUnknownAttribute
            ->method('getContentType')
            ->willReturn(100);
        $someUnknownAttribute
            ->method('getAnchors')
            ->willReturn($this->createMock(\Traversable::class));

        $someAttributeList = $this->createMock(AttributeList::class);
        $someAttributeList
            ->method('getAttributes')
            ->willReturn([
                $someAttribute,
                $someNullNameAttribute,
                $someUnknownAttribute,
            ]);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList($someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $yotiAttributesList);
    }
}
