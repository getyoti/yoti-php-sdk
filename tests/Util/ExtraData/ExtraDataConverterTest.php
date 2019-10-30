<?php

namespace YotiTest\Util\Profile;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Entity\ExtraData;
use Yoti\Util\ExtraData\ExtraDataConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\ExtraData\ExtraDataConverter
 */
class ExtraDataConverterTest extends TestCase
{
    /**
     * @covers ::convertValue
     */
    public function testConvertValue()
    {
        $extraData = ExtraDataConverter::convertValue(EXTRA_DATA_CONTENT);

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertInstanceOf(
            AttributeIssuanceDetails::class,
            $extraData->getAttributeIssuanceDetails()
        );
    }
}
