<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Extension;

use Yoti\Identity\Extension\LocationConstraintExtensionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Extension\LocationConstraintExtensionBuilder
 */
class LocationConstraintExtensionBuilderTest extends TestCase
{
    private const TYPE_LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';

    /**
     * @covers ::withLatitude
     */
    public function testLatitudeTooLow()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'latitude\' value \'-91\' is less than \'-90\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(-91)
            ->withLongitude(0)
            ->build();
    }

    /**
     * @covers ::withLatitude
     */
    public function testLatitudeTooHigh()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'latitude\' value \'91\' is greater than \'90\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(91)
            ->withLongitude(0)
            ->build();
    }

    /**
     * @covers ::withLongitude
     */
    public function testLongitudeTooLow()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'longitude\' value \'-181\' is less than \'-180\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(-181)
            ->build();
    }

    /**
     * @covers ::withLongitude
     */
    public function testLongitudeTooHigh()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'longitude\' value \'181\' is greater than \'180\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(181)
            ->build();
    }

    /**
     * @covers ::withRadius
     */
    public function testRadiusLessThanZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'radius\' value \'-1\' is less than \'0\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(0)
            ->withRadius(-1)
            ->build();
    }

    /**
     * @covers ::withMaxUncertainty
     */
    public function testMaxUncertaintyLessThanZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('\'maxUncertainty\' value \'-1\' is less than \'0\'');

        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(0)
            ->withMaxUncertainty(-1)
            ->build();
    }

    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $expectedLatitude = 50.8169;
        $expectedLongitude = -0.1367;
        $expectedRadius = 30;
        $expectedMaxUncertainty = 40;

        $extension = (new LocationConstraintExtensionBuilder())
            ->withLatitude($expectedLatitude)
            ->withLongitude($expectedLongitude)
            ->withRadius($expectedRadius)
            ->withMaxUncertainty($expectedMaxUncertainty)
            ->build();

        $expectedJson = json_encode([
            'type' => self::TYPE_LOCATION_CONSTRAINT,
            'content' => [
                'expected_device_location' => [
                    'latitude' => $expectedLatitude,
                    'longitude' => $expectedLongitude,
                    'radius' => $expectedRadius,
                    'max_uncertainty_radius' => $expectedMaxUncertainty,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($extension));
    }

    /**
     * @covers ::build
     */
    public function testBuildDefaultValues()
    {
        $expectedLatitude = 50.8169;
        $expectedLongitude = -0.1367;
        $expectedDefaultRadius = 150;
        $expectedDefaultMaxUncertainty = 150;

        $extension = (new LocationConstraintExtensionBuilder())
            ->withLatitude($expectedLatitude)
            ->withLongitude($expectedLongitude)
            ->build();

        $expectedJson = json_encode([
            'type' => self::TYPE_LOCATION_CONSTRAINT,
            'content' => [
                'expected_device_location' => [
                    'latitude' => $expectedLatitude,
                    'longitude' => $expectedLongitude,
                    'radius' => $expectedDefaultRadius,
                    'max_uncertainty_radius' => $expectedDefaultMaxUncertainty,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($extension));
    }
}
