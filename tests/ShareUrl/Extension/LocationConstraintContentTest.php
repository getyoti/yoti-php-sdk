<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\LocationConstraintContent;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Extension\LocationConstraintContent
 */
class LocationConstraintContentTest extends TestCase
{
    private const TYPE_LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';
    private const SOME_LATITUDE = 50.8169;
    private const SOME_LONGITUDE = -0.1367;

    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testBuild()
    {
        $expectedLatitude = 50.8169;
        $expectedLongitude = -0.1367;
        $expectedRadius = 30;
        $expectedmaxUncertainty = 40;

        $content = new LocationConstraintContent(
            $expectedLatitude,
            $expectedLongitude,
            $expectedRadius,
            $expectedmaxUncertainty
        );

        $expectedJson = json_encode([
            'expected_device_location' => [
                'latitude' => $expectedLatitude,
                'longitude' => $expectedLongitude,
                'radius' => $expectedRadius,
                'max_uncertainty_radius' => $expectedmaxUncertainty,
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($content));
        $this->assertEquals($expectedJson, $content);
    }
}
