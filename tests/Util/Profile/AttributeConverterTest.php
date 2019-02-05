<?php

namespace YotiTest\Util\Profile;

use YotiTest\TestCase;
use Yoti\Entity\Image;
use Yoti\Entity\Receipt;
use Yoti\ActivityDetails;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Exception\AttributeException;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AttributeConverter
 */
class AttributeConverterTest extends TestCase
{
    /**
     * Mocks \Attrpubapi\Attribute with provided name and value.
     */
    private function getMockForProtobufAttribute($name, $value) {
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
    public function testConvertToYotiAttribute() {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute('test_attr', 'my_value'));
        $this->assertEquals('test_attr', $attr->getName());
        $this->assertEquals('my_value', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeNullValue() {
        $attr = AttributeConverter::convertToYotiAttribute($this->getMockForProtobufAttribute('test_attr', ''));
        $this->assertNull($attr);
    }

    /**
     * @covers ::convertValueBasedOnContentType
     * @covers ::validateInput
     */
    public function testConvertValueBasedOnContentTypeValidation() {
        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Warning: test_attr value is NULL');
        $this->invokeStaticMethod(
            AttributeConverter::class,
            'convertValueBasedOnContentType',
            [$this->getMockForProtobufAttribute('test_attr', '')]
        );
    }

    /**
     * @covers ::convertValueBasedOnAttributeName
     * @covers ::validateInput
     */
    public function testConvertValueBasedOnAttributeNameValidation() {
        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Warning: test_attr value is NULL');
        $this->invokeStaticMethod(
            AttributeConverter::class,
            'convertValueBasedOnAttributeName',
            ['', 'test_attr']
        );
    }

    /**
     * @covers ::validateInput
     */
    public function testValidateInput() {
        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage('Warning: test_attr value is NULL');
        $this->invokeStaticMethod(
            AttributeConverter::class,
            'validateInput',
            ['', 'test_attr']
        );
    }

}
