<?php

namespace YotiTest\Util\Profile;

use Yoti\Util\ExtraData\ThirdPartyAttributeConverter;
use YotiTest\TestCase;
use Yoti\Protobuf\Sharepubapi\Definition;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;

/**
 * @coversDefaultClass \Yoti\Util\ExtraData\ThirdPartyAttributeConverter
 */
class ThirdPartyAttributeConverterTest extends TestCase
{
    const SOME_ISSUANCE_TOKEN = 'some issuance token';
    const SOME_OTHER_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.other_id';
    const SOME_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.id';
    const SOME_EXPIRY_DATE = '2019-12-02T12:00:00.123Z';

    /**
     * @covers ::convertValue
     */
    public function testConvertValue()
    {
        $thirdPartyAttribute = ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                self::SOME_ISSUANCE_TOKEN,
                self::SOME_EXPIRY_DATE,
                [
                    [ 'name' => self::SOME_ISSUING_ATTRIBUTE_NAME ],
                    [ 'name' => self::SOME_OTHER_ISSUING_ATTRIBUTE_NAME ],
                ]
            )
        );

        $this->assertEquals(base64_encode(self::SOME_ISSUANCE_TOKEN), $thirdPartyAttribute->getToken());
        $this->assertEquals(new \DateTime(self::SOME_EXPIRY_DATE), $thirdPartyAttribute->getExpiryDate());
        $this->assertEquals(
            self::SOME_ISSUING_ATTRIBUTE_NAME,
            $thirdPartyAttribute->getIssuingAttributes()[0]->getName()
        );
        $this->assertEquals(
            self::SOME_OTHER_ISSUING_ATTRIBUTE_NAME,
            $thirdPartyAttribute->getIssuingAttributes()[1]->getName()
        );
    }

    /**
     * @covers ::convertValue
     * @covers ::parseToken
     *
     * @dataProvider invalidTokenProvider
     *
     * @expectedException \Yoti\Exception\ExtraDataException
     * @expectedExceptionMessafe Failed to retrieve token from ThirdPartyAttribute
     */
    public function testConvertValueEmptyToken($invalidToken)
    {
        ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                $invalidToken,
                self::SOME_EXPIRY_DATE,
                []
            )
        );
    }

    /**
     * Provides invalid token values.
     */
    public function invalidTokenProvider()
    {
        return [
            [ '' ],
            [ null ],
            [ false ],
            [ 0 ],
        ];
    }

    /**
     * @covers ::convertValue
     *
     * @dataProvider invalidDateProvider
     */
    public function testConvertValueInvalidDate($invalidExpiryDate)
    {
        $this->captureExpectedLogs();

        $thirdPartyAttribute = ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                self::SOME_ISSUANCE_TOKEN,
                $invalidExpiryDate,
                [
                    [ 'name' => self::SOME_ISSUING_ATTRIBUTE_NAME ],
                ]
            )
        );

        $this->assertEquals(base64_encode(self::SOME_ISSUANCE_TOKEN), $thirdPartyAttribute->getToken());
        $this->assertNull($thirdPartyAttribute->getExpiryDate());
        $this->assertEquals(
            self::SOME_ISSUING_ATTRIBUTE_NAME,
            $thirdPartyAttribute->getIssuingAttributes()[0]->getName()
        );
        $this->assertLogContains('Failed to parse expiry date from ThirdPartyAttribute');
    }

    /**
     * Provides dates that are not RFC3339 with milliseconds.
     */
    public function invalidDateProvider()
    {
        return [
            [ '' ],
            [ 1 ],
            [ 'invalid date' ],
            [ '2019-12-02' ],
            [ '2019-12-02' ],
            [ '2019-12-02T12:00:00Z' ],
        ];
    }

    /**
     * @param string $token
     * @param string $expiryDate
     * @param array $definitions
     *
     * @return string serialized ThirdPartyAttribute
     */
    private function createThirdPartyAttribute($token, $expiryDate, $definitions)
    {
        return (new ThirdPartyAttribute([
            'issuance_token' => $token,
            'issuing_attributes' => new IssuingAttributes([
                'expiry_date' => $expiryDate,
                'definitions' => array_map(
                    function ($definition) {
                        return new Definition($definition);
                    },
                    $definitions
                )
            ]),
        ]))->serializeToString();
    }
}
