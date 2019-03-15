<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Entity\Image;
use Yoti\Entity\Receipt;
use Yoti\ActivityDetails;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Entity\MultiValue;

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
    const CONTENT_TYPE_PNG = 4;
    const CONTENT_TYPE_MULTI_VALUE = 6;

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
    public function testConvertToYotiAttributeNullValue()
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute('test_attr', ''));
        $this->assertNull($attr);
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

        $multiValue = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertEquals(2, count($multiValue->getValue()));
        $this->assertTrue(is_array($multiValue->getValue()));

        $this->assertIsExpectedImage($multiValue->getValue()[0], 'image/jpeg', 'vWgD//2Q==');
        $this->assertIsExpectedImage($multiValue->getValue()[1], 'image/jpeg', '38TVEH/9k=');
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
        $protoMultiValue->setValues($this->createTestMultiValueItems());

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->getMockForProtobufAttribute(
            'document_images',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $multiValue = $attr->getValue();

        $this->assertEquals(2, count($multiValue));
        $this->assertTrue(is_array($multiValue));

        $this->assertInstanceOf(Image::class, $multiValue[0]);
        $this->assertEquals('image/jpeg', $multiValue[0]->getMimeType());
        $this->assertNotEmpty($multiValue[0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[1]);
        $this->assertEquals('image/png', $multiValue[1]->getMimeType());
        $this->assertNotEmpty($multiValue[1]->getContent());
    }

    /**
     * Check that MultiValue object is returned for MultiValue attributes by default.
     *
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeMultiValue()
    {
        // Get MultiValue values.
        $values = $this->createTestMultiValueItems();

        // Add a nested MultiValue.
        $values[] = $this->createTestNestedMultiValueItem();

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
        $this->assertEquals(count($values), count($multiValue));
        $this->assertInstanceOf(MultiValue::class, $multiValue);

        $this->assertInstanceOf(Image::class, $multiValue[0]);
        $this->assertEquals('image/jpeg', $multiValue[0]->getMimeType());
        $this->assertNotEmpty($multiValue[0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[1]);
        $this->assertEquals('image/png', $multiValue[1]->getMimeType());
        $this->assertNotEmpty($multiValue[1]->getContent());

        $this->assertEquals('test string', $multiValue[2]);

        $this->assertNull($multiValue[3]);

        $this->assertInstanceOf(MultiValue::class, $multiValue[4]);

        // Check nested items.
        $this->assertEquals(4, count($multiValue[4]));

        $this->assertInstanceOf(Image::class, $multiValue[4][0]);
        $this->assertEquals('image/jpeg', $multiValue[4][0]->getMimeType());
        $this->assertNotEmpty($multiValue[4][0]->getContent());

        $this->assertInstanceOf(Image::class, $multiValue[4][1]);
        $this->assertEquals('image/png', $multiValue[4][1]->getMimeType());
        $this->assertNotEmpty($multiValue[4][1]->getContent());

        $this->assertEquals('test string', $multiValue[4][2]);
    }

    /**
     * Creates a nested MultiValue Value.
     *
     * @return \Attrpubapi\MultiValue\Value
     */
    private function createTestNestedMultiValueItem()
    {
        $multiValue = new \Attrpubapi\MultiValue();
        $multiValue->setValues($this->createTestMultiValueItems());
        $multiValueValue = new \Attrpubapi\MultiValue\Value();
        $multiValueValue->setData($multiValue->serializeToString());
        $multiValueValue->setContentType(self::CONTENT_TYPE_MULTI_VALUE);
        return $multiValueValue;
    }

    /**
     * Created an array of MultiValue image items.
     *
     * @return \Attrpubapi\MultiValue\Value[]
     */
    private function createTestMultiValueItems()
    {
        $createValues = [
            ['image 1', self::CONTENT_TYPE_JPEG],
            ['image 2', self::CONTENT_TYPE_PNG],
            ['test string', self::CONTENT_TYPE_STRING],
            ['', self::CONTENT_TYPE_STRING],
        ];

        $values = [];

        foreach ($createValues as $createValue) {
            $protoMultiValueValue = new \Attrpubapi\MultiValue\Value();
            $protoMultiValueValue->setData($createValue[0]);
            $protoMultiValueValue->setContentType($createValue[1]);
            $values[] = $protoMultiValueValue;
        }

        return $values;
    }
}
