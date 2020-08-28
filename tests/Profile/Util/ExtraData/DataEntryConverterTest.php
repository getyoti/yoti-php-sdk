<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Profile\Util\ExtraData\DataEntryConverter;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\ExtraData\DataEntryConverter
 */
class DataEntryConverterTest extends TestCase
{
    private const TYPE_THIRD_PARTY_ATTRIBUTE = 6;

    /**
     * @var \Yoti\Profile\Util\ExtraData\DataEntryConverter;
     */
    private $dataEntryConverter;

    public function setup(): void
    {
        $this->dataEntryConverter = new DataEntryConverter(
            $this->createMock(LoggerInterface::class)
        );
    }

    /**
     * @covers ::convert
     * @covers ::__construct
     */
    public function testConvertThirdPartyAttribute()
    {
        $thirdPartyAttribute = $this->dataEntryConverter->convert(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute([
                'issuance_token' => 'some token',
            ]))->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueThirdPartyAttribute()
    {
        $thirdPartyAttribute = DataEntryConverter::convertValue(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute([
                'issuance_token' => 'some token',
            ]))->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convert
     */
    public function testConvertValueThirdPartyAttributeEmptyValue()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Value is empty');

        $thirdPartyAttribute = $this->dataEntryConverter->convert(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute())->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convert
     */
    public function testConvertValueEmpty()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Value is empty');

        $this->dataEntryConverter->convert(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            ''
        );
    }

    /**
     * @covers ::convert
     */
    public function testConvertValueUnknown()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Unsupported data entry');

        $this->dataEntryConverter->convert(
            100,
            'Some value'
        );
    }
}
