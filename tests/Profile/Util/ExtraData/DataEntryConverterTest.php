<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\ExtraData;

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
     * @covers ::convertValue
     */
    public function testConvertValueThirdPartyAttributeEmptyValue()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Value is empty');

        $thirdPartyAttribute = DataEntryConverter::convertValue(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute())->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueEmpty()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Value is empty');

        DataEntryConverter::convertValue(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            ''
        );
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueUnknown()
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Unsupported data entry');

        DataEntryConverter::convertValue(
            100,
            'Some value'
        );
    }
}
