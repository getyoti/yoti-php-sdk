<?php

namespace YotiTest\Profile\Util\Attribute;

use Yoti\Profile\Util\Attribute\AttributeConverter;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\Attribute;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Protobuf\Compubapi\EncryptedData;
use YotiTest\TestCase;
use YotiTest\TestData;

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

    /**
     * @covers ::convertToProtobufAttributeList
     */
    public function testConvertToProtobufAttributeList()
    {
        $receiptArr = json_decode(file_get_contents(TestData::RECEIPT_JSON), true)['receipt'];

        $encryptedData = new EncryptedData();
        $encryptedData->mergeFromString(base64_decode($receiptArr['profile_content']));

        $protoAttributeList = AttributeListConverter::convertToProtobufAttributeList(
            $encryptedData,
            $receiptArr['wrapped_receipt_key'],
            file_get_contents(TestData::PEM_FILE)
        );

        $this->assertInstanceOf(AttributeList::class, $protoAttributeList);
        $this->assertCount(4, $protoAttributeList->getAttributes());
    }
}
