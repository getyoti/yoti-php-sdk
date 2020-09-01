<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util\ExtraData;

use Psr\Log\LoggerInterface;
use Yoti\Exception\DateTimeException;
use Yoti\Profile\Util\ExtraData\ThirdPartyAttributeConverter;
use Yoti\Protobuf\Sharepubapi\Definition;
use Yoti\Protobuf\Sharepubapi\IssuingAttributes;
use Yoti\Protobuf\Sharepubapi\ThirdPartyAttribute;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\ExtraData\ThirdPartyAttributeConverter
 */
class ThirdPartyAttributeConverterTest extends TestCase
{
    private const SOME_ISSUANCE_TOKEN = 'some issuance token';
    private const SOME_OTHER_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.other_id';
    private const SOME_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.id';
    private const SOME_EXPIRY_DATE = '2019-12-02T12:00:00.123Z';

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
     * @covers ::parseToken
     */
    public function testConvert()
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
     */
    public function testConvertValueEmptyToken($invalidToken)
    {
        $this->expectException(\Yoti\Exception\ExtraDataException::class);
        $this->expectExceptionMessage('Failed to retrieve token from ThirdPartyAttribute');

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
        ];
    }

    /**
     * @covers ::convertValue
     *
     * @dataProvider invalidDateProvider
     */
    public function testConvertValueInvalidDate($invalidExpiryDate, $expectedException)
    {
        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Failed to parse expiry date from ThirdPartyAttribute',
                $this->callback(function ($context) use ($expectedException) {
                    $this->assertInstanceOf($expectedException, $context['exception']);
                    return true;
                })
            );

        $thirdPartyAttribute = ThirdPartyAttributeConverter::convertValue(
            $this->createThirdPartyAttribute(
                self::SOME_ISSUANCE_TOKEN,
                $invalidExpiryDate,
                [
                    [ 'name' => self::SOME_ISSUING_ATTRIBUTE_NAME ],
                ]
            ),
            $this->logger
        );

        $this->assertEquals(base64_encode(self::SOME_ISSUANCE_TOKEN), $thirdPartyAttribute->getToken());
        $this->assertNull($thirdPartyAttribute->getExpiryDate());
        $this->assertEquals(
            self::SOME_ISSUING_ATTRIBUTE_NAME,
            $thirdPartyAttribute->getIssuingAttributes()[0]->getName()
        );
    }

    /**
     * Provides dates that are not RFC3339 with milliseconds.
     */
    public function invalidDateProvider()
    {
        return [
            [ '', \InvalidArgumentException::class ],
            [ 1, DateTimeException::class ],
            [ 'invalid date', DateTimeException::class],
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
        $thirdPartyAttribute = new ThirdPartyAttribute();

        if (isset($token)) {
            $thirdPartyAttribute->setIssuanceToken($token);
        }

        $issuingAttributes = new IssuingAttributes();

        if (isset($expiryDate)) {
            $issuingAttributes->setExpiryDate($expiryDate);
        }

        if (isset($definitions)) {
            $issuingAttributes->setDefinitions(array_map(
                function ($definition) {
                    return new Definition($definition);
                },
                $definitions
            ));
        }

        $thirdPartyAttribute->setIssuingAttributes($issuingAttributes);

        return $thirdPartyAttribute->serializeToString();
    }
}
