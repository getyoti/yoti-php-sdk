<?php

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Constants;
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
    private const SOME_PRIVACY_POLICY_URL = 'somePrivacyPolicyUrl';
    private const SOME_CATEGORY = 'someCategory';
    private const SOME_NUMBER_RETRIES = 5;
    private const SOME_BIOMETRIC_CONSENT_FLOW = 'someBiometricConsentFlow';


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
     * @covers ::withPrivacyPolicyUrl
     * @covers ::withAllowHandoff
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAllowedCaptureMethods
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getPrimaryColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getSecondaryColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getFontColour
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getLocale
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getPresetIssuingCountry
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getSuccessUrl
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getErrorUrl
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getPrivacyPolicyUrl
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAllowHandoff
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
            ->withPrivacyPolicyUrl(self::SOME_PRIVACY_POLICY_URL)
            ->withAllowHandoff(true)
            ->build();

        $this->assertEquals(self::SOME_CAPTURE_METHOD, $result->getAllowedCaptureMethods());
        $this->assertEquals(self::SOME_PRIMARY_COLOUR, $result->getPrimaryColour());
        $this->assertEquals(self::SOME_SECONDARY_COLOUR, $result->getSecondaryColour());
        $this->assertEquals(self::SOME_FONT_COLOUR, $result->getFontColour());
        $this->assertEquals(self::SOME_LOCALE, $result->getLocale());
        $this->assertEquals(self::SOME_PRESET_ISSUING_COUNTRY, $result->getPresetIssuingCountry());
        $this->assertEquals(self::SOME_SUCCESS_URL, $result->getSuccessUrl());
        $this->assertEquals(self::SOME_ERROR_URL, $result->getErrorUrl());
        $this->assertEquals(self::SOME_PRIVACY_POLICY_URL, $result->getPrivacyPolicyUrl());
        $this->assertTrue($result->getAllowHandoff());
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
            ->withPrivacyPolicyUrl(self::SOME_PRIVACY_POLICY_URL)
            ->withAllowHandoff(true)
            ->withBiometricConsentFlow(self::SOME_BIOMETRIC_CONSENT_FLOW)
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
            'privacy_policy_url' => self::SOME_PRIVACY_POLICY_URL,
            'allow_handoff' => true,
            'biometric_consent_flow' => self::SOME_BIOMETRIC_CONSENT_FLOW
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::jsonSerialize
     */
    public function shouldSerializeToEmptyObjectWithNoValuesSet()
    {
        $result = (new SdkConfigBuilder())->build();

        $this->assertJsonStringEqualsJsonString('{}', json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SdkConfigBuilder::build
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAllowHandoff
     */
    public function allowHandoffShouldBeNullWhenItIsNotSet()
    {
        $result = (new SdkConfigBuilder())
            ->withAllowedCaptureMethod(self::SOME_CAPTURE_METHOD)
            ->withPrimaryColour(self::SOME_PRIMARY_COLOUR)
            ->withSecondaryColour(self::SOME_SECONDARY_COLOUR)
            ->withFontColour(self::SOME_FONT_COLOUR)
            ->withLocale(self::SOME_LOCALE)
            ->withPresetIssuingCountry(self::SOME_PRESET_ISSUING_COUNTRY)
            ->build();

        $this->assertNull($result->getAllowHandoff());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withIdDocumentTextExtractionCategoryRetries
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAttemptsConfiguration
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::getIdDocumentTextDataExtraction
     */
    public function shouldBuildWithIdDocumentTextExtractionCategoryRetries(): void
    {
        $data = [
            self::SOME_CATEGORY => self::SOME_NUMBER_RETRIES
        ];

        $sdkConfig = (new SdkConfigBuilder())
            ->withIdDocumentTextExtractionCategoryRetries(self::SOME_CATEGORY, self::SOME_NUMBER_RETRIES)
            ->build();

        $this->assertEquals($data, $sdkConfig->getAttemptsConfiguration()->getIdDocumentTextDataExtraction());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAttemptsConfiguration
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     */
    public function attemptsConfigurationShouldBeNullIfNotSet(): void
    {
        $sdkConfig = (new SdkConfigBuilder())
            ->build();

        $this->assertNull($sdkConfig->getAttemptsConfiguration());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withIdDocumentTextExtractionCategoryRetries
     * @covers ::withIdDocumentTextExtractionReclassificationRetries
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAttemptsConfiguration
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::getIdDocumentTextDataExtraction
     */
    public function attemptsConfigurationShouldResetSameValueWithRepeatedCalls(): void
    {
        $data = [
            Constants::RECLASSIFICATION => 4
        ];

        $sdkConfig = (new SdkConfigBuilder())
            ->withIdDocumentTextExtractionReclassificationRetries(2)
            ->withIdDocumentTextExtractionReclassificationRetries(3)
            ->withIdDocumentTextExtractionReclassificationRetries(4)
            ->build();

        $this->assertCount(1, $sdkConfig->getAttemptsConfiguration()->getIdDocumentTextDataExtraction());
        $this->assertEquals($data, $sdkConfig->getAttemptsConfiguration()->getIdDocumentTextDataExtraction());
        $this->assertArrayHasKey(
            Constants::ID_DOCUMENT_TEXT_DATA_EXTRACTION,
            (array)$sdkConfig->getAttemptsConfiguration()
        );
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withIdDocumentTextExtractionCategoryRetries
     * @covers ::withIdDocumentTextExtractionReclassificationRetries
     * @covers ::withIdDocumentTextExtractionGenericAttempts
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::getAttemptsConfiguration
     * @covers \Yoti\DocScan\Session\Create\SdkConfig::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::__construct
     * @covers \Yoti\DocScan\Session\Create\AttemptsConfiguration::getIdDocumentTextDataExtraction
     */
    public function attemptsConfigurationShouldAllowMultipleCategories(): void
    {
        $numberOfGenericRetries = 3;
        $numberOfReclassificationRetries = 1;

        $sdkConfig = (new SdkConfigBuilder())
            ->withIdDocumentTextExtractionReclassificationRetries($numberOfReclassificationRetries)
            ->withIdDocumentTextExtractionGenericAttempts($numberOfGenericRetries)
            ->withIdDocumentTextExtractionCategoryRetries(self::SOME_CATEGORY, self::SOME_NUMBER_RETRIES)
            ->build();

        $this->assertCount(3, $sdkConfig->getAttemptsConfiguration()->getIdDocumentTextDataExtraction());
        $this->assertArrayHasKey(
            Constants::RECLASSIFICATION,
            $sdkConfig->getAttemptsConfiguration()
                ->getIdDocumentTextDataExtraction()
        );
        $this->assertArrayHasKey(
            Constants::GENERIC,
            $sdkConfig->getAttemptsConfiguration()
                ->getIdDocumentTextDataExtraction()
        );
        $this->assertArrayHasKey(
            self::SOME_CATEGORY,
            $sdkConfig->getAttemptsConfiguration()
                ->getIdDocumentTextDataExtraction()
        );

        $this->assertContains(
            $numberOfGenericRetries,
            $sdkConfig->getAttemptsConfiguration()
                ->getIdDocumentTextDataExtraction()
        );
        $this->assertContains(
            $numberOfReclassificationRetries,
            $sdkConfig->getAttemptsConfiguration()
            ->getIdDocumentTextDataExtraction()
        );
        $this->assertContains(
            self::SOME_NUMBER_RETRIES,
            $sdkConfig->getAttemptsConfiguration()
            ->getIdDocumentTextDataExtraction()
        );
    }
}
