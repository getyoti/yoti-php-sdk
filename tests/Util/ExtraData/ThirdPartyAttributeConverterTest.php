<?php

namespace YotiTest\Util\Profile;

use Yoti\Sharepubapi\Definition;
use Yoti\Sharepubapi\IssuingAttributes;
use Yoti\Sharepubapi\ThirdPartyAttribute;
use Yoti\Util\ExtraData\ThirdPartyAttributeConverter;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\ExtraData\ThirdPartyAttributeConverter
 */
class ThirdPartyAttributeConverterTest extends TestCase
{
    const SOME_ISSUANCE_TOKEN = 'some issuance token';
    const SOME_OTHER_ISSUING_ATTRIBUTE_NAME = 'com.other.thirdparty.id';
    const SOME_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.id';

    /**
     * @covers ::convertValue
     */
    public function testConvertValue()
    {
        $someExpiryDate = '2019-12-02T12:00:00.000Z';

        $thirdPartyAttribute = ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                self::SOME_ISSUANCE_TOKEN,
                $someExpiryDate,
                [
                [ 'name' => self::SOME_ISSUING_ATTRIBUTE_NAME ],
                [ 'name' => self::SOME_OTHER_ISSUING_ATTRIBUTE_NAME ],
                ]
            )
        );

        $this->assertEquals(self::SOME_ISSUANCE_TOKEN, $thirdPartyAttribute->getToken());
        $this->assertEquals($someExpiryDate, $thirdPartyAttribute->getExpiryDate()->format('Y-m-d\TH:i:s.ve'));
        $this->assertEquals(self::SOME_ISSUING_ATTRIBUTE_NAME, $thirdPartyAttribute->getIssuingAttributes()[0]);
        $this->assertEquals(self::SOME_OTHER_ISSUING_ATTRIBUTE_NAME, $thirdPartyAttribute->getIssuingAttributes()[1]);
    }

    /**
     * @covers ::convertValue
     *
     * @dataProvider invalidDateProvider
     */
    public function testConvertValueInvalidDate($someExpiryDate)
    {
        $this->captureExpectedLogs();

        $thirdPartyAttribute = ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                self::SOME_ISSUANCE_TOKEN,
                $someExpiryDate,
                [
                [ 'name' => self::SOME_ISSUING_ATTRIBUTE_NAME ],
                ]
            )
        );

        $this->assertEquals(self::SOME_ISSUANCE_TOKEN, $thirdPartyAttribute->getToken());
        $this->assertNull($thirdPartyAttribute->getExpiryDate());
        $this->assertEquals(self::SOME_ISSUING_ATTRIBUTE_NAME, $thirdPartyAttribute->getIssuingAttributes()[0]);

        $this->assertLogContains(sprintf(
            "Failed to parse expiry date '%s' from ThirdPartyAttribute using format 'Y-m-d\TH:i:s.vP'",
            $someExpiryDate,
        ));
    }

    /**
     * Provides dates that are not RFC3339 with milliseconds.
     */
    public function invalidDateProvider()
    {
        return [
        ['2019-12-02T12:00:00.000000Z'],
        ['2019-12-02'],
        ['2019-12-02'],
        ['invalid'],
        [''],
        ];
    }

    /**
     * @param string $token
     * @param string $expiryDate
     * @param array $definitions
     *
     * @return \Yoti\Sharepubapi\ThirdPartyAttribute
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
