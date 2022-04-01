<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\Attribute;

use Psr\Log\LoggerInterface;
use Yoti\Exception\AttributeException;
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
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Value is NULL (Attribute: some-attribute)',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(AttributeException::class, $context['exception']);
                    return true;
                })
            );

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

        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList($someAttributeList, $logger);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertContainsOnlyInstancesOf(Attribute::class, $yotiAttributesList);
    }
}
