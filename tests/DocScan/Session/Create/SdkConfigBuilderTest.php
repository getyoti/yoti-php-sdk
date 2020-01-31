<?php

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\SdkConfigBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\SdkConfigBuilder
 */
class SdkConfigBuilderTest extends TestCase
{

    private const SOME_CAPTURE_METHOD = 'someCaptureMethod';
    private const SOME_PRIMARY_COLOUR = 'somePrimaryColour';
    private const SOME_SECONDARY_COLOUR = 'someSecondaryColour';
    private const SOME_FONT_COLOUR = 'someFontColour';
    private const SOME_LOCALE = 'someLocale';
    private const SOME_PRESET_ISSUING_COUNTRY = 'somePresetIssuingCountry';
    private const SOME_SUCCESS_URL = 'someSuccessUrl';
    private const SOME_ERROR_URL = 'someErrorUrl';

    /**
     * @test
     * @covers ::build
     * @covers ::withAllowedCaptureMethod
     * @covers ::withPrimaryColour
     * @covers ::withSecondaryColour
     * @covers ::withFontColour
     * @covers ::withLocale
     * @covers ::withPresetIssuingCountry
     * @covers ::withSuccessUrl
     * @covers ::withErrorUrl
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAllowedCaptureMethods
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getPrimaryColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getSecondaryColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getFontColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getLocale
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getPresetIssuingCountry
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getSuccessUrl
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getErrorUrl
     */
    public function shouldCorrectlyBuildSdkConfig()
    {
        $result = (new SdkConfigBuilder())
            ->withAllowedCaptureMethod(self::SOME_CAPTURE_METHOD)
            ->withPrimaryColour(self::SOME_PRIMARY_COLOUR)
            ->withSecondaryColour(self::SOME_SECONDARY_COLOUR)
            ->withFontColour(self::SOME_FONT_COLOUR)
            ->withLocale(self::SOME_LOCALE)
            ->withPresetIssuingCountry(self::SOME_PRESET_ISSUING_COUNTRY)
            ->withSuccessUrl(self::SOME_SUCCESS_URL)
            ->withErrorUrl(self::SOME_ERROR_URL)
            ->build();

        $this->assertEquals(self::SOME_CAPTURE_METHOD, $result->getAllowedCaptureMethods());
        $this->assertEquals(self::SOME_PRIMARY_COLOUR, $result->getPrimaryColour());
        $this->assertEquals(self::SOME_SECONDARY_COLOUR, $result->getSecondaryColour());
        $this->assertEquals(self::SOME_FONT_COLOUR, $result->getFontColour());
        $this->assertEquals(self::SOME_LOCALE, $result->getLocale());
        $this->assertEquals(self::SOME_PRESET_ISSUING_COUNTRY, $result->getPresetIssuingCountry());
        $this->assertEquals(self::SOME_SUCCESS_URL, $result->getSuccessUrl());
        $this->assertEquals(self::SOME_ERROR_URL, $result->getErrorUrl());
    }

    /**
     * @test
     * @covers ::withAllowsCamera
     */
    public function shouldSetCorrectValueWithAllowsCamera()
    {
        $result = (new SdkConfigBuilder())
            ->withAllowsCamera()
            ->build();

        $this->assertEquals('CAMERA', $result->getAllowedCaptureMethods());
    }

    /**
     * @test
     * @covers ::withAllowsCameraAndUpload
     */
    public function shouldSetCorrectValuesWithAllowsCameraAndUpload()
    {
        $result = (new SdkConfigBuilder())
            ->withAllowsCameraAndUpload()
            ->build();

        $this->assertEquals('CAMERA_AND_UPLOAD', $result->getAllowedCaptureMethods());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::jsonSerialize
     */
    public function shouldProduceTheCorrectJsonString()
    {
        $result = (new SdkConfigBuilder())
            ->withAllowedCaptureMethod(self::SOME_CAPTURE_METHOD)
            ->withPrimaryColour(self::SOME_PRIMARY_COLOUR)
            ->withSecondaryColour(self::SOME_SECONDARY_COLOUR)
            ->withFontColour(self::SOME_FONT_COLOUR)
            ->withLocale(self::SOME_LOCALE)
            ->withPresetIssuingCountry(self::SOME_PRESET_ISSUING_COUNTRY)
            ->withSuccessUrl(self::SOME_SUCCESS_URL)
            ->withErrorUrl(self::SOME_ERROR_URL)
            ->build();

        $expected = [
            'allowed_capture_methods' => self::SOME_CAPTURE_METHOD,
            'primary_colour' => self::SOME_PRIMARY_COLOUR,
            'secondary_colour' => self::SOME_SECONDARY_COLOUR,
            'font_colour' => self::SOME_FONT_COLOUR,
            'locale' => self::SOME_LOCALE,
            'preset_issuing_country' => self::SOME_PRESET_ISSUING_COUNTRY,
            'success_url' => self::SOME_SUCCESS_URL,
            'error_url' => self::SOME_ERROR_URL,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
