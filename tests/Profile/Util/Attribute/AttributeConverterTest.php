<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\Attribute;

use Psr\Log\LoggerInterface;
use Yoti\Exception\AttributeException;
use Yoti\Exception\DateTimeException;
use Yoti\Media\Image;
use Yoti\Profile\Attribute;
use Yoti\Profile\Attribute\MultiValue;
use Yoti\Profile\Util\Attribute\AttributeConverter;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Attribute\AttributeConverter
 */
class AttributeConverterTest extends TestCase
{
    /**
     * Content Types.
     */
    private const CONTENT_TYPE_UNDEFINED = 0;
    private const CONTENT_TYPE_STRING = 1;
    private const CONTENT_TYPE_JPEG = 2;
    private const CONTENT_TYPE_DATE = 3;
    private const CONTENT_TYPE_PNG = 4;
    private const CONTENT_TYPE_JSON = 5;
    private const CONTENT_TYPE_MULTI_VALUE = 6;
    private const CONTENT_TYPE_INT = 7;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function setup(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * Creates \Yoti\Protobuf\Attrpubapi\Attribute with provided name and value.
     */
    private function createProtobufAttribute($name, $value, $contentType = self::CONTENT_TYPE_STRING)
    {
        return new \Yoti\Protobuf\Attrpubapi\Attribute([
            'name' => $name,
            'value' => $value,
            'content_type' => $contentType,
            'anchors' => new \Google\Protobuf\Internal\RepeatedField(
                \Google\Protobuf\Internal\GPBType::MESSAGE,
                \Yoti\Protobuf\Attrpubapi\Anchor::class
            ),
        ]);
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttribute()
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->createProtobufAttribute('test_attr', 'my_value'));
        $this->assertEquals('test_attr', $attr->getName());
        $this->assertEquals('my_value', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     */
    public function testConvertDate()
    {
        $someTimeStamp = '2016-07-19T08:55:38Z';
        $attr = AttributeConverter::convertToYotiAttribute($this->createProtobufAttribute(
            'test_attr',
            $someTimeStamp,
            self::CONTENT_TYPE_DATE
        ));
        $this->assertEquals(
            $someTimeStamp,
            $attr->getValue()->format('Y-m-d\Th:i:s\Z')
        );
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testEmptyApplicationLogo()
    {
        $attr = AttributeConverter::convertToYotiAttribute($this->createProtobufAttribute(
            'application_logo',
            '',
            self::CONTENT_TYPE_PNG
        ));
        $this->assertNull($attr);
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     */
    public function testConvertDateInvalid()
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Could not parse string to DateTime',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(DateTimeException::class, $context['exception']);
                    return true;
                })
            );

        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'test_attr',
                'some-invalid-date',
                self::CONTENT_TYPE_DATE
            ),
            $this->logger
        );
        $this->assertNull($attr);
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     */
    public function testConvertJson()
    {
        $someJsonData = ['some' => 'json'];
        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'test_attr',
                json_encode($someJsonData),
                self::CONTENT_TYPE_JSON
            ),
            $this->logger
        );
        $this->assertEquals($someJsonData, $attr->getValue());
    }

    /**
     * @covers ::convertValueBasedOnAttributeName
     */
    public function testConvertDocumentDetails()
    {
        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'document_details',
                'PASSPORT GBR 01234567 2020-01-01',
                self::CONTENT_TYPE_STRING
            ),
            $this->logger
        );
        $document = $attr->getValue();
        $this->assertEquals('PASSPORT', $document->getType());
        $this->assertEquals('GBR', $document->getIssuingCountry());
        $this->assertEquals('01234567', $document->getDocumentNumber());
        $this->assertEquals('2020-01-01', $document->getExpirationDate()->format('Y-m-d'));
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     */
    public function testConvertUndefinedContentType()
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with("Unknown Content Type '0', parsing as a String");

        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute('undefined_attr', 'undefined_value', self::CONTENT_TYPE_UNDEFINED),
            $this->logger
        );
        $this->assertEquals('undefined_attr', $attr->getName());
        $this->assertEquals('undefined_value', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     * @covers ::convertValueBasedOnAttributeName
     */
    public function testConvertUnknownContentType()
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with("Unknown Content Type '100', parsing as a String");

        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute('unknown_attr', 'unknown_value', 100),
            $this->logger
        );
        $this->assertEquals('unknown_attr', $attr->getName());
        $this->assertEquals('unknown_value', $attr->getValue());
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testConvertToYotiAttributeEmptyStringValue()
    {
        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'test_attr',
                '',
                self::CONTENT_TYPE_STRING
            ),
            $this->logger
        );
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
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Value is NULL (Attribute: test_attr)',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(AttributeException::class, $context['exception']);
                    return true;
                })
            );

        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'test_attr',
                '',
                $contentType
            ),
            $this->logger
        );

        $this->assertNull($attr);
    }

    /**
     * @covers ::convertToYotiAttribute
     * @covers ::convertValueBasedOnContentType
     *
     * @dataProvider validIntegerDataProvider
     */
    public function testConvertToYotiAttributeIntegerValue($int)
    {
        $attr = AttributeConverter::convertToYotiAttribute(
            $this->createProtobufAttribute(
                'test_attr',
                (string) $int,
                self::CONTENT_TYPE_INT
            ),
            $this->logger
        );
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
     * @covers ::convertValueBasedOnAttributeName
     */
    public function testConvertToYotiAttributeDocumentImages()
    {
        $decodedProtoString = base64_decode(file_get_contents(TestData::MULTI_VALUE_ATTRIBUTE));

        $protobufAttribute = new \Yoti\Protobuf\Attrpubapi\Attribute();
        $protobufAttribute->mergeFromString($decodedProtoString);

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertCount(2, $attr->getValue());
        $this->assertInstanceOf(MultiValue::class, $attr->getValue());

        $this->assertIsExpectedImage($attr->getValue()[0], 'image/jpeg', 'vWgD//2Q==');
        $this->assertIsExpectedImage($attr->getValue()[1], 'image/jpeg', '38TVEH/9k=');
    }

    /**
     * Asserts that provided image is expected.
     *
     * @param \Yoti\Media\Image $image
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
     * @covers ::convertValueBasedOnAttributeName
     * @covers ::convertMultiValue
     */
    public function testConvertToYotiAttributeDocumentImagesFiltered()
    {
        // Create top-level MultiValue.
        $protoMultiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
        $protoMultiValue->setValues($this->createMultiValueValues());

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->createProtobufAttribute(
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
     * @covers ::convertValueBasedOnAttributeName
     */
    public function testConvertToYotiAttributeDocumentImagesInvalid()
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Document Images could not be decoded (Attribute: document_images)',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(AttributeException::class, $context['exception']);
                    return true;
                })
            );

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->createProtobufAttribute(
            'document_images',
            'invalid value',
            self::CONTENT_TYPE_STRING
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute, $this->logger);
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
        $protoMultiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
        $protoMultiValue->setValues($values);

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->createProtobufAttribute(
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
     * @covers ::convertValueBasedOnContentType
     *
     * @dataProvider nonStringContentTypesDataProvider
     */
    public function testEmptyNonStringAttributeMultiValueValue($contentType)
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Value is NULL (Attribute: test_attr)',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(AttributeException::class, $context['exception']);
                    return true;
                })
            );

        // Get MultiValue values.
        $values = $this->createMultiValueValues();

        // Add an empty MultiValue.
        $values[] = $this->createMultiValueValue('', $contentType);

        // Create top-level MultiValue.
        $protoMultiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
        $protoMultiValue->setValues($values);

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->createProtobufAttribute(
            'test_attr',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute, $this->logger);
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
        $protoMultiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
        $protoMultiValue->setValues($values);

        // Create mock Attribute that will return MultiValue as the value.
        $protobufAttribute = $this->createProtobufAttribute(
            'test_attr',
            $protoMultiValue->serializeToString(),
            self::CONTENT_TYPE_MULTI_VALUE
        );

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);
        $this->assertInstanceOf(Attribute::class, $attr);
        $this->assertEquals('', $attr->getValue()[3]);
    }

    /**
     * @covers ::convertToYotiAttribute
     */
    public function testThirdPartyAttribute()
    {
        $decodedProtoString = base64_decode(file_get_contents(TestData::THIRD_PARTY_ATTRIBUTE));

        $protobufAttribute = new \Yoti\Protobuf\Attrpubapi\Attribute();
        $protobufAttribute->mergeFromString($decodedProtoString);

        $attr = AttributeConverter::convertToYotiAttribute($protobufAttribute);

        $this->assertEquals('test-third-party-attribute-0', $attr->getValue());
        $this->assertEquals('com.thirdparty.id', $attr->getName());

        $this->assertEquals('THIRD_PARTY', $attr->getSources()[0]->getValue());
        $this->assertEquals('orgName', $attr->getSources()[0]->getSubType());

        $this->assertEquals('THIRD_PARTY', $attr->getVerifiers()[0]->getValue());
        $this->assertEquals('orgName', $attr->getVerifiers()[0]->getSubType());
    }

    /**
     * Creates a nested MultiValue Value.
     *
     * @return \Yoti\Protobuf\Attrpubapi\MultiValue\Value
     */
    private function createNestedMultiValueValue()
    {
        $multiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
        $multiValue->setValues($this->createMultiValueValues());

        return $this->createMultiValueValue($multiValue->serializeToString(), self::CONTENT_TYPE_MULTI_VALUE);
    }

    /**
     * Created an array of MultiValue image items.
     *
     * @return \Yoti\Protobuf\Attrpubapi\MultiValue\Value[]
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
     * @return \Yoti\Protobuf\Attrpubapi\MultiValue\Value
     */
    private function createMultiValueValue($data, $contentType)
    {
        $protoMultiValueValue = new \Yoti\Protobuf\Attrpubapi\MultiValue\Value();
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
            [ self::CONTENT_TYPE_JSON ],
            [ self::CONTENT_TYPE_MULTI_VALUE ],
            [ self::CONTENT_TYPE_INT ],
        ];
    }
}
