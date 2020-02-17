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
     * @covers ::__construct
     * @covers ::getCode
     */
    public function testGetCode()
    {
        $country = new Country(self::SOME_COUNTRY_CODE);

        $this->assertEquals(self::SOME_COUNTRY_CODE, $country->getCode());
    }
}
