<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\ExtraData;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\ExtraData\AttributeIssuanceDetails
 */
class AttributeIssuanceDetailsTest extends TestCase
{
    private const SOME_ISSUANCE_TOKEN = 'some issuance token';
    private const SOME_ISSUING_ATTRIBUTE_NAME = 'com.thirdparty.id';
    private const SOME_EXPIRY_DATE = '2019-12-02T12:00:00.000Z';

    /**
     * @var \Yoti\Profile\ExtraData\AttributeIssuanceDetails
     */
    private $attributeIssuanceDetails;

    public function setup(): void
    {
        $mockAttributeDefinition = $this->createMock(AttributeDefinition::class);
        $mockAttributeDefinition
            ->method('getName')
            ->willReturn(self::SOME_ISSUING_ATTRIBUTE_NAME);

        $this->attributeIssuanceDetails = new AttributeIssuanceDetails(
            self::SOME_ISSUANCE_TOKEN,
            new \DateTime(self::SOME_EXPIRY_DATE),
            [ $mockAttributeDefinition ]
        );
    }

    /**
     * @covers ::getToken
     * @covers ::__construct
     */
    public function testGetToken()
    {
        $this->assertEquals(
            self::SOME_ISSUANCE_TOKEN,
            $this->attributeIssuanceDetails->getToken()
        );
    }

    /**
     * @covers ::getExpiryDate
     * @covers ::__construct
     */
    public function testGetExpiryDate()
    {
        $this->assertEquals(
            new \DateTime(self::SOME_EXPIRY_DATE),
            $this->attributeIssuanceDetails->getExpiryDate()
        );
    }

    /**
     * @covers ::getIssuingAttributes
     * @covers ::__construct
     */
    public function testGetIssuingAttributes()
    {
        $this->assertEquals(
            self::SOME_ISSUING_ATTRIBUTE_NAME,
            $this->attributeIssuanceDetails->getIssuingAttributes()[0]->getName()
        );
    }

    /**
     * @covers ::__construct
     *
     *
     * @dataProvider invalidTokenDataProvider
     */
    public function testInvalidToken($invalidToken)
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('%s::__construct()', AttributeIssuanceDetails::class));

        new AttributeIssuanceDetails($invalidToken);
    }

    /**
     * Provides non-string token values.
     */
    public function invalidTokenDataProvider()
    {
        return [
            [1],
            [0],
            [[]],
            [true],
            [false],
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testInvalidIssuingAttributes()
    {
        $this->expectException(
            \InvalidArgumentException::class,
            'issuingAttributes must be array of Yoti\\Profile\\ExtraData\\AttributeDefinition'
        );

        new AttributeIssuanceDetails(
            'some token',
            new \DateTime(),
            [1, 0, [], true, false]
        );
    }
}
