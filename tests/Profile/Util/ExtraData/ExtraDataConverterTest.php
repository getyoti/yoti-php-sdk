<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Exception\ExtraDataException;
use Yoti\Profile\ExtraData;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Profile\Util\ExtraData\ExtraDataConverter;
use Yoti\Protobuf\Sharepubapi\DataEntry;
use Yoti\Protobuf\Sharepubapi\ExtraData as ExtraDataProto;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;

/**
 * @coversDefaultClass \Yoti\Profile\Util\ExtraData\ExtraDataConverter
 */
class ExtraDataConverterTest extends TestCase
{
    private const TYPE_THIRD_PARTY_ATTRIBUTE = 6;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function setup(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @covers ::convertValue
     */
    public function testConvert()
    {
        $extraData = ExtraDataConverter::convertValue(base64_decode(file_get_contents(TestData::EXTRA_DATA_CONTENT)));
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
        $this->logger
            ->expects($this->exactly(2))
            ->method('warning')
            ->withConsecutive(
                [
                    'Failed to convert data entry',
                    $this->callback(function ($context) {
                        $this->assertInstanceOf(ExtraDataException::class, $context['exception']);
                        return true;
                    })
                ],
                [
                    'Failed to convert data entry',
                    $this->callback(function ($context) {
                        $this->assertInstanceOf(ExtraDataException::class, $context['exception']);
                        return true;
                    })
                ]
            );

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

        $extraData = ExtraDataConverter::convertValue($extraDataContent, $this->logger);

        $this->assertEquals(base64_encode($someToken), $extraData->getAttributeIssuanceDetails()->getToken());
    }

    /**
     * @covers ::convertValue
     */
    public function testConvertValueInvalidData()
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Failed to parse extra data',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(\Exception::class, $context['exception']);
                    $this->assertStringContainsString(
                        'Error occurred during parsing',
                        $context['exception']->getMessage()
                    );
                    return true;
                })
            );

        $extraData = ExtraDataConverter::convertValue('some invalid data', $this->logger);

        $this->assertInstanceOf(ExtraData::class, $extraData);
        $this->assertNull($extraData->getAttributeIssuanceDetails());
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
