<?php

declare(strict_types=1);

namespace Yoti\Test\Aml;

use Yoti\Aml\Country;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Aml\Country
 */
class CountryTest extends TestCase
{
    private const SOME_COUNTRY_CODE = 'GBR';

    /**
     * @var Country
     */
    private $country;

    public function setup(): void
    {
        $this->country = new Country(self::SOME_COUNTRY_CODE);
    }

    /**
     * @covers ::__construct
     * @covers ::getCode
     */
    public function testGetCode()
    {
        $this->assertEquals(
            self::SOME_COUNTRY_CODE,
            $this->country->getCode()
        );
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode(self::SOME_COUNTRY_CODE),
            json_encode($this->country)
        );
    }
}
