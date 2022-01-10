<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Instructions\Branch;

use Yoti\DocScan\Session\Retrieve\Instructions\Branch\LocationResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Instructions\Branch\LocationResponse
 */
class LocationResponseTest extends TestCase
{
    private const SOME_LATITUDE = 0.0873;
    private const SOME_LONGITUDE = 0.836793;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getLatitude
     * @covers ::getLongitude
     */
    public function shouldBuildCorrectly(): void
    {
        $data = [
            'latitude' => self::SOME_LATITUDE,
            'longitude' => self::SOME_LONGITUDE,
        ];

        $result = new LocationResponse($data);

        $this->assertEquals(self::SOME_LONGITUDE, $result->getLongitude());
        $this->assertEquals(self::SOME_LATITUDE, $result->getLatitude());
    }
}
