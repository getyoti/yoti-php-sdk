<?php

namespace YotiTest\Aml;

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Aml\Address
 */
class AddressTest extends TestCase
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
        $amlAddress = new Address($someCountry);

        $this->assertEquals($someCountry, $amlAddress->getCountry());
    }

    /**
     * @covers ::setCountry
     */
    public function testSetCountry()
    {
        $amlAddress = new Address($this->createMock(Country::class));
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
        $amlAddress = new Address(
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
        $amlAddress = new Address($this->createMock(Country::class));
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

        $amlAddress = new Address($someCountry, self::SOME_POSTCODE);

        $expectedData = [
            'post_code' => self::SOME_POSTCODE,
            'country' => self::SOME_COUNTRY_CODE,
        ];

        $this->assertEquals($expectedData, $amlAddress->jsonSerialize());
        $this->assertEquals(json_encode($expectedData), json_encode($amlAddress));
        $this->assertEquals(json_encode($expectedData), (string) $amlAddress);
    }
}
