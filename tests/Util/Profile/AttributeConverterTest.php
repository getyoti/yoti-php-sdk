<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Entity\Image;
use Yoti\Entity\Receipt;
use Yoti\ActivityDetails;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Entity\MultiValue;
use Yoti\Entity\Attribute;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AttributeConverter
 */
class AttributeConverterTest extends TestCase
{
    /**
     * Content Types.
     */
    const CONTENT_TYPE_STRING = 1;
    const CONTENT_TYPE_JPEG = 2;
    const CONTENT_TYPE_DATE = 3;
    const CONTENT_TYPE_PNG = 4;
    const CONTENT_TYPE_BYTES = 5;
    const CONTENT_TYPE_MULTI_VALUE = 6;
    const CONTENT_TYPE_INT = 7;

    /**
     * Mocks \Attrpubapi\Attribute with provided name and value.
     */
    private function getMockForProtobufAttribute($name, $value, $contentType = self::CONTENT_TYPE_STRING)
    {
        // Setup protobuf mock.
        $protobufAttribute = $this->getMockBuilder(\Attrpubapi\Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();
        $protobufAttribute
            ->method('getAnchors')
            ->willReturn($this->getMockBuilder(\Traversable::class)->getMock());
        $protobufAttribute
            ->method('getName')
            ->willReturn($name);
        $protobufAttribute
            ->method('getValue')
            ->willReturn($value);
        $protobufAttribute
            ->method('getContentType')
            ->willReturn($contentType);

        return $protobufAttribute;
    }

    /**
     * @covers ::convertTimestampToDate
     */
    public function testDateTypeShouldReturnDateTime()
    {
        $dateTime = AttributeConverter::convertTimestampToDate('1980/12/01');
        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertEquals('01-12-1980', $dateTime->format('d-m-Y'));
    }

    /**
     * @covers ::convertValueBasedOnContentType
     */
    public function testSelfieValueShouldReturnImageObject()
    {
        $pem = file_get_contents(PEM_FILE);
        $receiptArr = json_decode(file_get_contents(RECEIPT_JSON), true);
        $receipt = new Receipt($receiptArr['receipt']);

        $this->activityDetails = new ActivityDetails($receipt, $pem);
        $this->profile = $this->activityDetails->getProfile();
        $this->assertInstanceOf(Image::class, $this->profile->getSelfie()->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttribute()
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute('test_attr', 'my_value'));
        $this->assertEquals('test_attr', $attr->getName());
        $this->assertEquals('my_value', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeEmptyStringValue()
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute(
            'test_attr',
            '',
            self::CONTENT_TYPE_STRING
        ));
        $this->assertEquals('test_attr', $attr->getName());
        $this->assertEquals('', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     *
     * @dataProvider nonStringContentTypesDataProvider
     */
    public function testConvertToYotiAttributeEmptyNonStringValue($contentType)
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute(
            'test_attr',
            '',
            $contentType
        ));
        $this->assertNull($attr);
    }

    /**
     * @covers ::convertToYotiAttribute
     *
     * @dataProvider validIntegerDataProvider
     */
    public function testConvertToYotiAttributeIntegerValue($int)
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute(
            'test_attr',
            $int,
            self::CONTENT_TYPE_INT
        ));
        $this->assertSame($int, $attr->getValue());
    }

    /**
     * Provides list of valid integers.
     */
    public function validIntegerDataProvider()
    {
        return [[0], [1], [123], [-1], [-10]];
    }

    /**
     * Check that `document_images` attribute is an array of 2 images.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeDocumentImages()
    {
        $protobufAttribute = new \Attrpubapi\Attribute();
        $protobufAttribute->mergeFromString(base64_decode(MULTI_VALUE_ATTRIBUTE));

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertCount(2, $attr->getValue());
        $this->assertInstanceOf(MultiValue::class, $attr->getValue());

        $this->assertIsExpectedImage($attr->getValue()[0], 'image/jpeg', 'vWgD//2Q==');
        $this->assertIsExpectedImage($attr->getValue()[1], 'image/jpeg', '38TVEH/9k=');
    }

    /**
     * Asserts that provided image is expected.
     *
     * @param \Yoti\Entity\Image $image
     * @param string $mimeType
     * @param string $base64last10
     */
    private function assertIsExpectedImage($image, $mimeType, $base64last10)
    {
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($mimeType, $image->getMimeType());
        $this->assertNotEmpty($image->getContent());
        $this->assertEquals(substr($image->getBase64Content(), -10), $base64last10);
    }

    /**
     * Check that `document_images` attribute has non-image values filtered out.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeDocumentImagesFiltered()
    {
        // Create top-level MultiValue.
        $protoMultiValue = new \Attrpubapi\MultiValue();
        $protoMultiValue->setValues($this->createMultiValueValues());

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->getMockForProtobufAttribute(
            'document_images',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $multiValue = $attr->getValue();

        $this->assertCount(2, $multiValue);
        $this->assertInstanceOf(MultiValue::class, $multiValue);

        $this->assertInstanceOf(Image::class, $multiValue[0]);
        $this->assertEquals('image/jpeg', $multiValue[0]->getMimeType());
        $this->assertNotEmpty($multiValue[0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[1]);
        $this->assertEquals('image/png', $multiValue[1]->getMimeType());
        $this->assertNotEmpty($multiValue[1]->getContent());
    }

    /**
     * Check that `document_images` is null when not converted to a MultiValue object.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeDocumentImagesInvalid()
    {
        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->getMockForProtobufAttribute(
            'document_images',
            'invalid value',
            self::CONTENT_TYPE_STRING
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertNull($attr);
    }

    /**
     * Check that MultiValue object is returned for MultiValue attributes by default.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeMultiValue()
    {
        // Get MultiValue values.
        $values = $this->createMultiValueValues();

        // Add a nested MultiValue.
        $values[] = $this->createNestedMultiValueValue();

        // Create top-level MultiValue.
        $protoMultiValue = new \Attrpubapi\MultiValue();
        $protoMultiValue->setValues($values);

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->getMockForProtobufAttribute(
            'test_attr',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        // Convert the attribute.
        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $multiValue = $attr->getValue();

        // Check top-level items.
        $this->assertCount(4, $multiValue);
        $this->assertInstanceOf(MultiValue::class, $multiValue);

        $this->assertInstanceOf(Image::class, $multiValue[0]);
        $this->assertEquals('image/jpeg', $multiValue[0]->getMimeType());
        $this->assertNotEmpty($multiValue[0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[1]);
        $this->assertEquals('image/png', $multiValue[1]->getMimeType());
        $this->assertNotEmpty($multiValue[1]->getContent());

        $this->assertEquals('test string', $multiValue[2]);

        $this->assertInstanceOf(MultiValue::class, $multiValue[3]);

        // Check nested items.
        $this->assertCount(3, $multiValue[3]);

        $this->assertInstanceOf(Image::class, $multiValue[3][0]);
        $this->assertEquals('image/jpeg', $multiValue[3][0]->getMimeType());
        $this->assertNotEmpty($multiValue[3][0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[3][1]);
        $this->assertEquals('image/png', $multiValue[3][1]->getMimeType());
        $this->assertNotEmpty($multiValue[3][1]->getContent());

        $this->assertEquals('test string', $multiValue[3][2]);
    }

    /**
     * Check that empty non-string MultiValue Values result in no attribute being returned.
     *
     * @covers ::convertToYotiAttribute
     *
     * @dataProvider nonStringContentTypesDataProvider
     */
    public function testEmptyNonStringAttributeMultiValueValue($contentType)
    {
        // Get MultiValue values.
        $values = $this->createMultiValueValues();

        // Add an empty MultiValue.
        $values[] = $this->createMultiValueValue('', $contentType);

        // Create top-level MultiValue.
        $protoMultiValue = new \Attrpubapi\MultiValue();
        $protoMultiValue->setValues($values);

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->getMockForProtobufAttribute(
            'test_attr',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertNull($attr);
    }

    /**
     * Check that empty string MultiValue Values are allowed.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testEmptyStringAttributeMultiValueValue()
    {
         // Get MultiValue values.
         $values = $this->createMultiValueValues();

         // Add an empty MultiValue.
         $values[] = $this->createMultiValueValue('', self::CONTENT_TYPE_STRING);

         // Create top-level MultiValue.
         $protoMultiValue = new \Attrpubapi\MultiValue();
         $protoMultiValue->setValues($values);

         // Create mock Attribute that will return MultiValue as the value.
         $protobufAttribute = $this->getMockForProtobufAttribute(
             'test_attr',
             $protoMultiValue->serializeToString(),
             self::CONTENT_TYPE_MULTI_VALUE
         );

         $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
         $this->assertInstanceOf(Attribute::class, $attr);
         $this->assertEquals('', $attr->getValue()[3]);
    }

    /**
     * Creates a nested MultiValue Value.
     *
     * @return \Attrpubapi\MultiValue\Value
     */
    private function createNestedMultiValueValue()
    {
        $multiValue = new \Attrpubapi\MultiValue();
        $multiValue->setValues($this->createMultiValueValues());

        return $this->createMultiValueValue($multiValue->serializeToString(), self::CONTENT_TYPE_MULTI_VALUE);
    }

    /**
     * Created an array of MultiValue image items.
     *
     * @return \Attrpubapi\MultiValue\Value[]
     */
    private function createMultiValueValues()
    {
        $createValues = [
            ['image 1', self::CONTENT_TYPE_JPEG],
            ['image 2', self::CONTENT_TYPE_PNG],
            ['test string', self::CONTENT_TYPE_STRING],
        ];

        $items = [];

        foreach ($createValues as $createValue) {
            $items[] = $this->createMultiValueValue($createValue[0], $createValue[1]);
        }

        return $items;
    }

    /**
     * Create MultiValue Value.
     *
     * @param string $data
     * @param int $contentType
     *
     * @return \Attrpubapi\MultiValue\Value
     */
    private function createMultiValueValue($data, $contentType)
    {
        $protoMultiValueValue = new \Attrpubapi\MultiValue\Value();
        $protoMultiValueValue->setData($data);
        $protoMultiValueValue->setContentType($contentType);
        return $protoMultiValueValue;
    }

    /**
     * Provides non-string content types.
     */
    public function nonStringContentTypesDataProvider()
    {
        return [
            [ self::CONTENT_TYPE_JPEG ],
            [ self::CONTENT_TYPE_DATE ],
            [ self::CONTENT_TYPE_PNG ],
            [ self::CONTENT_TYPE_BYTES ],
            [ self::CONTENT_TYPE_MULTI_VALUE ],
            [ self::CONTENT_TYPE_INT ],
        ];
    }
}
