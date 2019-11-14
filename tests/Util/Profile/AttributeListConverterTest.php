<?php

namespace YotiTest\Util\Profile;

use Attrpubapi\Attribute;
use Attrpubapi\AttributeList;
use Compubapi\EncryptedData;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Util\Profile\AttributeListConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AttributeListConverter
 */
class AttributeListConverterTest extends TestCase
{
    const SOME_NAME = 'some name';
    const SOME_VALUE = 'some value';

    public function setup()
    {
        $someAttribute = $this->createMock(Attribute::class);
        $someAttribute
            ->method('getName')
            ->willReturn(self::SOME_NAME);
        $someAttribute
            ->method('getValue')
            ->willReturn(self::SOME_VALUE);
        $someAttribute
            ->method('getContentType')
            ->willReturn(AttributeConverter::CONTENT_TYPE_STRING);
        $someAttribute
            ->method('getAnchors')
            ->willReturn($this->createMock(\Traversable::class));

        $this->someAttributeList = $this->createMock(AttributeList::class);
        $this->someAttributeList
            ->method('getAttributes')
            ->willReturn([
                $someAttribute,
                $this->createMock(Attribute::class),
            ]);
    }

    /**
     * @covers ::convertToYotiAttributesMap
     */
    public function testConvertToYotiAttributesMap()
    {
        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesMap($this->someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertEquals(self::SOME_VALUE, $yotiAttributesList[self::SOME_NAME]->getValue());
    }

    /**
     * @covers ::convertToYotiAttributesList
     */
    public function testConvertToYotiAttributesList()
    {
        $yotiAttributesList = AttributeListConverter::convertToYotiAttributesList($this->someAttributeList);

        $this->assertCount(1, $yotiAttributesList);
        $this->assertEquals(self::SOME_VALUE, $yotiAttributesList[0]->getValue());
    }

    /**
     * @covers ::convertToProtobufAttributeList
     * @covers ::decryptCipherText
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
