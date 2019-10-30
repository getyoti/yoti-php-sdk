<?php

namespace YotiTest\Util\Profile;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Sharepubapi\ThirdPartyAttribute;
use Yoti\Util\ExtraData\DataEntryConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\ExtraData\DataEntryConverter
 */
class DataEntryConverterTest extends TestCase
{
    const THIRD_PARTY_ATTRIBUTE = 6;

    /**
     * @covers ::convertValue
     */
    public function testConvertValueThirdPartyAttribute()
    {
        $thirdPartyAttribute = DataEntryConverter::convertValue(
            self::THIRD_PARTY_ATTRIBUTE,
            (new ThirdPartyAttribute())->serializeToString()
        );

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $thirdPartyAttribute);
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueUnknown()
    {
        $this->captureExpectedLogs();

        $thirdPartyAttribute = DataEntryConverter::convertValue(
            'Some unknown type',
            ''
        );

        $this->assertLogContains("Skipping unsupported data entry");
        $this->assertNull($thirdPartyAttribute);
    }
}
