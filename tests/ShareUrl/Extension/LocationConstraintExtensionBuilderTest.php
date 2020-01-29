<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder
 */
class LocationConstraintExtensionBuilderTest extends TestCase
{
    private const TYPE_LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';
    private const SOME_LATITUDE = 50.8169;
    private const SOME_LONGITUDE = -0.1367;

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
        $expectedmaxUncertainty = 40;

        $extension = (new LocationConstraintExtensionBuilder())
            ->withLatitude($expectedLatitude)
            ->withLongitude($expectedLongitude)
            ->withRadius($expectedRadius)
            ->withMaxUncertainty($expectedmaxUncertainty)
            ->build();

        $expectedJson = json_encode([
            'type' => self::TYPE_LOCATION_CONSTRAINT,
            'content' => [
                'expected_device_location' => [
                    'latitude' => $expectedLatitude,
                    'longitude' => $expectedLongitude,
                    'radius' => $expectedRadius,
                    'max_uncertainty_radius' => $expectedmaxUncertainty,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($extension));
        $this->assertEquals($expectedJson, $extension);
    }

    /**
     * @covers ::build
     */
    public function testBuildDefaultValues()
    {
        $expectedLatitude = 50.8169;
        $expectedLongitude = -0.1367;
        $expectedDefaultRadius = 150;
        $expectedDefaultmaxUncertainty = 150;

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
                    'max_uncertainty_radius' => $expectedDefaultmaxUncertainty,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($extension));
        $this->assertEquals($expectedJson, $extension);
    }
}
