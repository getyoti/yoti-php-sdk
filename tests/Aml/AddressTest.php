<?php

declare(strict_types=1);

namespace Yoti\Test\Aml;

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Aml\Address
 */
class AddressTest extends TestCase
{
    private const SOME_POSTCODE = 'BN2 1TW';
    private const SOME_COUNTRY_CODE = 'GBR';

    /**
     * @var Country
     */
    private $countryMock;

    public function setup(): void
    {
        $this->countryMock = $this->createMock(Country::class);
        $this->countryMock
            ->method('jsonSerialize')
            ->willReturn(self::SOME_COUNTRY_CODE);
    }

    /**
     * @covers ::__construct
     * @covers ::getCountry
     */
    public function testGetCountry()
    {
        $amlAddress = new Address($this->countryMock);

        $this->assertEquals($this->countryMock, $amlAddress->getCountry());
    }

    /**
     * @covers ::__construct
     * @covers ::getPostcode
     */
    public function testGetPostcode()
    {
        $amlAddress = new Address(
            $this->countryMock,
            self::SOME_POSTCODE
        );

        $this->assertEquals(self::SOME_POSTCODE, $amlAddress->getPostcode());
    }

    /**
     * @covers ::__construct
     * @covers ::getPostcode
     */
    public function testGetPostcodeNull()
    {
        $amlAddress = new Address($this->countryMock);

        $this->assertNull($amlAddress->getPostcode());
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonSerialize()
    {
        $amlAddress = new Address($this->countryMock, self::SOME_POSTCODE);

        $expectedData = [
            'post_code' => self::SOME_POSTCODE,
            'country' => $this->countryMock,
        ];

        $this->assertEquals($expectedData, $amlAddress->jsonSerialize());

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            json_encode($amlAddress)
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            (string) $amlAddress
        );
    }
}
