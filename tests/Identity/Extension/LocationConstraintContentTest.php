<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Extension;

use Yoti\Identity\Extension\LocationConstraintContent;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Extension\LocationConstraintContent
 */
class LocationConstraintContentTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function testBuild()
    {
        $expectedLatitude = 50.8169;
        $expectedLongitude = -0.1367;
        $expectedRadius = 30;
        $expectedMaxUncertainty = 40;

        $content = new LocationConstraintContent(
            $expectedLatitude,
            $expectedLongitude,
            $expectedRadius,
            $expectedMaxUncertainty
        );

        $expectedJson = json_encode([
            'expected_device_location' => [
                'latitude' => $expectedLatitude,
                'longitude' => $expectedLongitude,
                'radius' => $expectedRadius,
                'max_uncertainty_radius' => $expectedMaxUncertainty,
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($content));
    }
}
