<?php

namespace YotiTest\Util\Profile;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Entity\ExtraData;
use Yoti\Sharepubapi\DataEntry;
use Yoti\Sharepubapi\ThirdPartyAttribute;
use Yoti\Sharepubapi\ExtraData as ExtraDataProto;
use Yoti\Util\ExtraData\ExtraDataConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\ExtraData\ExtraDataConverter
 */
class ExtraDataConverterTest extends TestCase
{
    const TYPE_THIRD_PARTY_ATTRIBUTE = 6;

    /**
     * @covers ::convertValue
     */
    public function testConvertValue()
    {
        $extraData = ExtraDataConverter::convertValue(EXTRA_DATA_CONTENT);

        $this->assertInstanceOf(ExtraData::class, $extraData);

        $attributeIssuanceDetails = $extraData->getAttributeIssuanceDetails();

        $this->assertInstanceOf(AttributeIssuanceDetails::class, $attributeIssuanceDetails);
        $this->assertEquals('someIssuanceToken', $attributeIssuanceDetails->getToken());
        $this->assertEquals(
            new \DateTime('2019-10-15T22:04:05.123000+0000'),
            $attributeIssuanceDetails->getExpiryDate()
        );
        $this->assertEquals([
            'com.thirdparty.id',
            'com.thirdparty.other_id',
        ], $attributeIssuanceDetails->getIssuingAttributes());
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueSkipInvalidDataEntries()
    {
        $this->captureExpectedLogs();

        $someToken = 'some token';

        $extraDataContent = (new ExtraDataProto([
            'list' => [
                (new DataEntry([
                    'type' => 0,
                    'value' => 'some value',
                ])),
                (new DataEntry([
                    'type' => self::TYPE_THIRD_PARTY_ATTRIBUTE,
                    'value' => (new ThirdPartyAttribute([
                        'issuance_token' => $someToken,
                    ]))->serializeToString()
                ])),
                (new DataEntry([
                    'type' => self::TYPE_THIRD_PARTY_ATTRIBUTE,
                    'value' => (new ThirdPartyAttribute([
                        'issuance_token' => 'some other token',
                    ]))->serializeToString()
                ]))
            ]
        ]))->serializeToString();

        $extraData = ExtraDataConverter::convertValue(base64_encode($extraDataContent));

        $this->assertEquals($extraData->getAttributeIssuanceDetails()->getToken(), $someToken);

        $this->assertLogContains("Failed to convert data entry: Unsupported data entry '0'");
    }

    /**
     * @covers ::convertValue
     *
     * @dataProvider invalidDataProvider
     */
    public function testConvertValueInvalidData($invalidData, $errorMessage)
    {
        $this->captureExpectedLogs();

        $extraData = ExtraDataConverter::convertValue($invalidData);

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertNull($extraData->getAttributeIssuanceDetails());
        $this->assertLogContains("Failed to parse extra data: {$errorMessage}");
    }

    /**
     * Provides invalid data values.
     */
    public function invalidDataProvider()
    {
        return [
            [
                'some invalid data',
                'Error occurred during parsing: Unexpected EOF inside length delimited data',
            ],
            [
                base64_encode('some invalid data'),
                'Error occurred during parsing: Unexpected wire type',
            ],
            [
                base64_encode(0),
                'Error occurred during parsing: Unexpected EOF inside varint',
            ],
            [
                base64_encode(1),
                'Error occurred during parsing: Unexpected EOF inside fixed64',
            ],
        ];
    }

    /**
     * @covers ::convertValue
     *
     * @dataProvider emptyDataProvider
     */
    public function testConvertValueEmptyData($emptyData)
    {
        $extraData = ExtraDataConverter::convertValue($emptyData);

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertNull($extraData->getAttributeIssuanceDetails());
    }

    /**
     * Provides empty data values.
     */
    public function emptyDataProvider()
    {
        return [
            [ '' ],
            [ null ],
        ];
    }
}
