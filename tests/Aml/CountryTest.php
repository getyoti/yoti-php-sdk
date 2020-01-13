<?php

namespace YotiTest\Aml;

use Yoti\Aml\Country;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Aml\Country
 */
class CountryTest extends TestCase
{
    const SOME_COUNTRY_CODE = 'GBR';

    /**
     * @covers ::__construct
     * @covers ::getCode
     */
    public function testGetCode()
    {
        $country = new Country(self::SOME_COUNTRY_CODE);

        $this->assertEquals(self::SOME_COUNTRY_CODE, $country->getCode());
    }

    /**
     * @covers ::setCode
     */
    public function testSetCode()
    {
        $country = new Country('');
        $country->setCode(self::SOME_COUNTRY_CODE);

        $this->assertEquals(self::SOME_COUNTRY_CODE, $country->getCode());
    }
}
