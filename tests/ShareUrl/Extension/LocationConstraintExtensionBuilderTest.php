<?php

namespace YotiTest\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\LocationConstraintExtensionBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\LocationConstraintExtensionBuilder
 */
class LocationConstraintExtensionBuilderTest extends TestCase
{
    const TYPE_LOCATION_CONSTRAINT = 'LOCATION_CONSTRAINT';
    const SOME_LATITUDE = 50.8169;
    const SOME_LONGITUDE = -0.1367;

    /**
     * @covers ::withLatitude
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'latitude' value '-91' is less than '-90'
     */
    public function testLatitudeTooLow()
    {
        (new LocationConstraintExtensionBuilder())
            ->withLatitude(-91)
            ->withLongitude(0)
            ->build();
    }

    /**
     * @covers ::withLatitude
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'latitude' value '91' is greater than '90'
     */
    public function testLatitudeTooHigh()
    {
        (new LocationConstraintExtensionBuilder())
            ->withLatitude(91)
            ->withLongitude(0)
            ->build();
    }

    /**
     * @covers ::withLongitude
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'longitude' value '-181' is less than '-180'
     */
    public function testLongitudeTooLow()
    {
        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(-181)
            ->build();
    }

    /**
     * @covers ::withLongitude
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'longitude' value '181' is greater than '180'
     */
    public function testLongitudeTooHigh()
    {
        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(181)
            ->build();
    }

    /**
     * @covers ::withRadius
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'radius' value '-1' is less than '0'
     */
    public function testRadiusLessThanZero()
    {
        (new LocationConstraintExtensionBuilder())
            ->withLatitude(0)
            ->withLongitude(0)
            ->withRadius(-1)
            ->build();
    }

    /**
     * @covers ::withMaxUncertainty
     *
     * @expectedException RangeException
     * @expectedExceptionMessage 'maxUncertainty' value '-1' is less than '0'
     */
    public function testMaxUncertaintyLessThanZero()
    {
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
