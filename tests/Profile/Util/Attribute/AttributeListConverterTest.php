<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\Attribute;

use Yoti\Profile\Attribute;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\Attribute as AttributeProto;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AttributeListConverter
 */
class AttributeListConverterTest extends TestCase
{
    private const CONTENT_TYPE_STRING = 1;

    /**
     * @covers ::convertToYotiAttributesList
     */
    public function testConvertToYotiAttributesList()
    {
        $this->captureExpectedLogs();

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
            ->willReturn(self::CONTENT_TYPE_STRING);
        $someAttribute
            ->method('getAnchors')
            ->willReturn($this->createMock(\Traversable::class));

        $someNullNameAttribute = $this->createMock(AttributeProto::class);
        $someNullNameAttribute
            ->method('getName')
            ->willReturn(null);

        $someEmptyNonStringAttribute = $this->createMock(AttributeProto::class);
        $someEmptyNonStringAttribute
            ->method('getName')
            ->willReturn('some-attribute');
        $someEmptyNonStringAttribute
            ->method('getValue')
            ->willReturn('');
        $someEmptyNonStringAttribute
            ->method('getContentType')
            ->willReturn(100);
        $someEmptyNonStringAttribute
            ->method('getAnchors')
            ->willReturn($this->createMock(\Traversable::class));

        $someAttributeList = $this->createMock(AttributeList::class);
        $someAttributeList
            ->method('getAttributes')
            ->willReturn([
                $someAttribute,
                $someNullNameAttribute,
                $someEmptyNonStringAttribute,
            ]);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList($someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $yotiAttributesList);
        $this->assertLogContains('Warning: Value is NULL (Attribute: some-attribute)');
    }
}
