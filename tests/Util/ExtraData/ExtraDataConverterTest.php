<?php

namespace YotiTest\Util\Profile;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Entity\ExtraData;
use Yoti\Util\ExtraData\ExtraDataConverter;
use YotiTest\TestCase;
use Yoti\Protobuf\Sharepubapi\DataEntry;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Protobuf\Sharepubapi\ExtraData as ExtraDataProto;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;

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
        $extraData = ExtraDataConverter::convertValue(base64_decode(EXTRA_DATA_CONTENT));
        $this->assertInstanceOf(ExtraData::class, $extraData);

        $attributeIssuanceDetails = $extraData->getAttributeIssuanceDetails();
        $this->assertInstanceOf(AttributeIssuanceDetails::class, $attributeIssuanceDetails);
        $this->assertEquals(base64_encode('someIssuanceToken'), $attributeIssuanceDetails->getToken());
        $this->assertEquals(
            new \DateTime('2019-10-15T22:04:05.123000+0000'),
            $attributeIssuanceDetails->getExpiryDate()
        );

        $issuingAttributes = $attributeIssuanceDetails->getIssuingAttributes();
        $this->assertEquals('com.thirdparty.id', $issuingAttributes[0]->getName());
        $this->assertEquals('com.thirdparty.other_id', $issuingAttributes[1]->getName());
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
                    'value' => $this->createThirdPartyAttribute(''),
                ])),
                (new DataEntry([
                    'type' => self::TYPE_THIRD_PARTY_ATTRIBUTE,
                    'value' => $this->createThirdPartyAttribute($someToken),
                ])),
                (new DataEntry([
                    'type' => self::TYPE_THIRD_PARTY_ATTRIBUTE,
                    'value' => $this->createThirdPartyAttribute('some other token'),
                ]))
            ]
        ]))->serializeToString();

        $extraData = ExtraDataConverter::convertValue($extraDataContent);

        $this->assertEquals(base64_encode($someToken), $extraData->getAttributeIssuanceDetails()->getToken());

        $this->assertLogContains("Failed to convert data entry: Unsupported data entry '0'");
        $this->assertLogContains("Failed to convert data entry: Failed to retrieve token from ThirdPartyAttribute");
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueInvalidData()
    {
        $this->captureExpectedLogs();

        $extraData = ExtraDataConverter::convertValue('some invalid data');

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertNull($extraData->getAttributeIssuanceDetails());
        $this->assertLogContains("Failed to parse extra data: Error occurred during parsing: Unexpected wire type");
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

    /**
     * @param string $token
     *
     * @return string serialized ThirdPartyAttribute
     */
    private function createThirdPartyAttribute($token)
    {
        return (new ThirdPartyAttribute([
            'issuance_token' => $token,
            'issuing_attributes' => new IssuingAttributes([
                'expiry_date' => '2019-12-02T12:00:00.000Z',
            ]),
        ]))->serializeToString();
    }
}
