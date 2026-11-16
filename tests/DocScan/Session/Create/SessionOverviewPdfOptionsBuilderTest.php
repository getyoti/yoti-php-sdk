<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\SessionOverviewPdfOptions;
use Yoti\DocScan\Session\Create\SessionOverviewPdfOptionsBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\SessionOverviewPdfOptionsBuilder
 */
class SessionOverviewPdfOptionsBuilderTest extends TestCase
{
    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::__construct
     */
    public function buildShouldReturnSessionOverviewPdfOptions(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())->build();

        $this->assertInstanceOf(SessionOverviewPdfOptions::class, $result);
    }

    /**
     * @test
     * @covers ::withIncludeImages
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeImages
     */
    public function builderShouldSetIncludeImages(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())
            ->withIncludeImages(true)
            ->build();

        $this->assertTrue($result->getIncludeImages());
    }

    /**
     * @test
     * @covers ::withIncludeExtractedText
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeExtractedText
     */
    public function builderShouldSetIncludeExtractedText(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())
            ->withIncludeExtractedText(true)
            ->build();

        $this->assertTrue($result->getIncludeExtractedText());
    }

    /**
     * @test
     * @covers ::withIncludeDeviceTracking
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeDeviceTracking
     */
    public function builderShouldSetIncludeDeviceTracking(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())
            ->withIncludeDeviceTracking(false)
            ->build();

        $this->assertFalse($result->getIncludeDeviceTracking());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeImages
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeExtractedText
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeDeviceTracking
     */
    public function shouldSerializeAllSetFieldsWithCorrectSnakeCaseKeys(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())
            ->withIncludeImages(true)
            ->withIncludeExtractedText(false)
            ->withIncludeDeviceTracking(true)
            ->build();

        $expected = [
            'include_images' => true,
            'include_extracted_text' => false,
            'include_device_tracking' => true,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::jsonSerialize
     */
    public function shouldOmitUnsetFieldsFromSerializedJson(): void
    {
        // Only includeImages is set; the other two must not appear in JSON.
        $result = (new SessionOverviewPdfOptionsBuilder())
            ->withIncludeImages(true)
            ->build();

        $decoded = json_decode(json_encode($result), true);

        $this->assertArrayHasKey('include_images', $decoded);
        $this->assertArrayNotHasKey('include_extracted_text', $decoded);
        $this->assertArrayNotHasKey('include_device_tracking', $decoded);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::jsonSerialize
     */
    public function shouldProduceEmptyObjectWhenNoFieldsAreSet(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())->build();

        $this->assertJsonStringEqualsJsonString('{}', json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::__construct
     * @covers \Yoti\DocScan\Session\Create\SessionOverviewPdfOptions::getIncludeImages
     */
    public function unsetFieldsShouldReturnNull(): void
    {
        $result = (new SessionOverviewPdfOptionsBuilder())->build();

        $this->assertNull($result->getIncludeImages());
        $this->assertNull($result->getIncludeExtractedText());
        $this->assertNull($result->getIncludeDeviceTracking());
    }
}
