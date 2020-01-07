<?php

namespace YotiTest\Profile\Util\ExtraData;

use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Profile\Util\ExtraData\DataEntryConverter;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\ExtraData\DataEntryConverter
 */
class DataEntryConverterTest extends TestCase
{
    const TYPE_THIRD_PARTY_ATTRIBUTE = 6;

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
     *
     * @expectedException \Yoti\Exception\ExtraDataException
     * @expectedExceptionMessage Value is empty
     */
    public function testConvertValueThirdPartyAttributeEmptyValue()
    {
        $thirdPartyAttribute = DataEntryConverter::convertValue(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute())->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convertValue
     *
     * @expectedException \Yoti\Exception\ExtraDataException
     * @expectedExceptionMessage Value is empty
     */
    public function testConvertValueEmpty()
    {
        DataEntryConverter::convertValue(
            self::TYPE_THIRD_PARTY_ATTRIBUTE,
            ''
        );
    }

    /**
     * @covers ::convertValue
     *
     * @expectedException \Yoti\Exception\ExtraDataException
     * @expectedExceptionMessage Unsupported data entry
     */
    public function testConvertValueUnknown()
    {
        DataEntryConverter::convertValue(
            'Some unknown type',
            'Some value'
        );
    }
}
