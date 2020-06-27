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

        $someAttribute = new AttributeProto([
            'name' => $someName,
            'value' => $someValue,
            'content_type' => self::CONTENT_TYPE_STRING,
            'anchors' => [],
        ]);

        $someEmptyNameAttribute = new AttributeProto();

        $someEmptyNonStringAttribute = new AttributeProto([
            'name' => 'some-attribute',
            'value' => '',
            'content_type' => 100,
            'anchors' => [],
        ]);

        $someAttributeList = new AttributeList([
            'attributes' => [
                $someAttribute,
                $someEmptyNameAttribute,
                $someEmptyNonStringAttribute,
            ],
        ]);

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList($someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $yotiAttributesList);
        $this->assertLogContains('Warning: Value is NULL (Attribute: some-attribute)');
    }
}
