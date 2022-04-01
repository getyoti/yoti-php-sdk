<?php

namespace Yoti\Test\DocScan\Session\Instructions\Branch;

use Yoti\DocScan\Session\Instructions\Branch\LocationBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Instructions\Branch\Location
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
     * @covers \Yoti\DocScan\Session\Instructions\Branch\LocationBuilder::withLongitude
     * @covers \Yoti\DocScan\Session\Instructions\Branch\LocationBuilder::withLatitude
     * @covers \Yoti\DocScan\Session\Instructions\Branch\LocationBuilder::build
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
