<?php

namespace YotiTest\Entity;

use Yoti\Entity\AmlAddress;
use YotiTest\TestCase;
use Yoti\Entity\Country;

/**
 * @coversDefaultClass \Yoti\Entity\AmlAddress
 */
class AmlAddressTest extends TestCase
{
    const SOME_POSTCODE = 'BN2 1TW';
    const SOME_COUNTRY_CODE = 'GBR';

    /**
     * @covers ::__construct
     * @covers ::getCountry
     */
    public function testGetCountry()
    {
        $someCountry = $this->createMock(Country::class);
        $amlAddress = new AmlAddress($someCountry);

        $this->assertEquals($someCountry, $amlAddress->getCountry());
    }

    /**
     * @covers ::setCountry
     */
    public function testSetCountry()
    {
        $amlAddress = new AmlAddress($this->createMock(Country::class));
        $someCountry = $this->createMock(Country::class);
        $amlAddress->setCountry($someCountry);

        $this->assertSame($someCountry, $amlAddress->getCountry());
    }

    /**
     * @covers ::__construct
     * @covers ::getPostcode
     */
    public function testGetPostcode()
    {
        $amlAddress = new AmlAddress(
            $this->createMock(Country::class),
            self::SOME_POSTCODE
        );

        $this->assertEquals(self::SOME_POSTCODE, $amlAddress->getPostcode());
    }

    /**
     * @covers ::setPostcode
     */
    public function testSetPostcode()
    {
        $amlAddress = new AmlAddress($this->createMock(Country::class));
        $amlAddress->setPostcode(self::SOME_POSTCODE);

        $this->assertEquals(self::SOME_POSTCODE, $amlAddress->getPostcode());
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonSerialize()
    {
        $someCountry = $this->createMock(Country::class);
        $someCountry
            ->method('getCode')
            ->willreturn(self::SOME_COUNTRY_CODE);

        $amlAddress = new AmlAddress($someCountry, self::SOME_POSTCODE);

        $expectedData = [
            'post_code' => self::SOME_POSTCODE,
            'country' => self::SOME_COUNTRY_CODE,
        ];

        $this->assertEquals($expectedData, $amlAddress->jsonSerialize());
        $this->assertEquals(json_encode($expectedData), json_encode($amlAddress));
        $this->assertEquals(json_encode($expectedData), (string) $amlAddress);
    }
}
