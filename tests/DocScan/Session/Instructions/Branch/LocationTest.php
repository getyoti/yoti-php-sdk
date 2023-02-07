<?php

namespace Yoti\Test\IDV\Session\Instructions\Branch;

use Yoti\IDV\Session\Instructions\Branch\LocationBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Instructions\Branch\Location
 */
class LocationTest extends TestCase
{
    private const SOME_LATITUDE = -40.4837;
    private const SOME_LONGITUDE = 90.47483;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLongitude
     * @covers ::getLatitude
     * @covers \Yoti\IDV\Session\Instructions\Branch\LocationBuilder::withLongitude
     * @covers \Yoti\IDV\Session\Instructions\Branch\LocationBuilder::withLatitude
     * @covers \Yoti\IDV\Session\Instructions\Branch\LocationBuilder::build
     */
    public function builderShouldBuildWithAllProperties()
    {
        $result = (new LocationBuilder())
            ->withLatitude(self::SOME_LATITUDE)
            ->withLongitude(self::SOME_LONGITUDE)
            ->build();

        $this->assertEquals(self::SOME_LONGITUDE, $result->getLongitude());
        $this->assertEquals(self::SOME_LATITUDE, $result->getLatitude());
    }
}
