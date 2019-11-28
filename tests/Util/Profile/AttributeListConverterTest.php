<?php

namespace YotiTest\Util\Profile;

use Yoti\Protobuf\Attrpubapi\Attribute;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Protobuf\Compubapi\EncryptedData;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Util\Profile\AttributeListConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AttributeListConverter
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
        $receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true)['receipt'];

        $encryptedData = new EncryptedData();
        $encryptedData->mergeFromString(base64_decode($receiptArr['profile_content']));

        $protoAttributeList = AttributeListConverter::convertToProtobufAttributeList(
            $encryptedData,
            $receiptArr['wrapped_receipt_key'],
            file_get_contents(PEM_FILE)
        );

        $this->assertInstanceOf(AttributeList::class, $protoAttributeList);
        $this->assertCount(4, $protoAttributeList->getAttributes());
    }
}
