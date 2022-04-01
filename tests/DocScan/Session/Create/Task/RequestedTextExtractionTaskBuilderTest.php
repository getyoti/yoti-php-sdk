<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTaskBuilder
 */
class RequestedTextExtractionTaskBuilderTest extends TestCase
{
    private const SOME_MANUAL_CHECK = 'someManualCheck';

    /**
     * @test
     * @covers ::withManualCheck
     * @covers ::setManualCheck
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::__construct
     */
    public function shouldBuildCorrectlyWithoutException()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheck(self::SOME_MANUAL_CHECK)
            ->build();

        $this->assertInstanceOf(RequestedTextExtractionTask::class, $result);
    }

    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getConfig
     */
    public function shouldUseAlwaysAsValueForManualCheck()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheckAlways()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => 'ALWAYS',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withManualCheckFallback
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getConfig
     */
    public function shouldUseFallbackAsValueForManualCheck()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheckFallback()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => 'FALLBACK',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withManualCheckNever
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getConfig
     */
    public function shouldUseNeverAsValueForManualCheck()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => 'NEVER',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::__toString
     */
    public function shouldCreateCorrectString()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheck(self::SOME_MANUAL_CHECK)
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => self::SOME_MANUAL_CHECK,
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }

    /**
     * @test
     * @covers ::withChipDataDesired
     * @covers ::withChipData
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getConfig
     */
    public function shouldUseDesiredAsValueForChipData()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheckAlways()
            ->withChipDataDesired()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => 'ALWAYS',
                'chip_data' => 'DESIRED',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withChipDataIgnore
     * @covers ::withChipData
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedTextExtractionTask::getConfig
     */
    public function shouldUseIgnoreAsValueForChipData()
    {
        $result = (new RequestedTextExtractionTaskBuilder())
            ->withManualCheckAlways()
            ->withChipDataIgnore()
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
            'config' => [
                'manual_check' => 'ALWAYS',
                'chip_data' => 'IGNORE',
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
