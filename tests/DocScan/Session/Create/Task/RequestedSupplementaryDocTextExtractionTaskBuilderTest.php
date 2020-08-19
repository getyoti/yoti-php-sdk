<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTaskBuilder
 */
class RequestedSupplementaryDocTextExtractionTaskBuilderTest extends TestCase
{
    private const SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION = 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION';
    private const ALWAYS = 'ALWAYS';
    private const FALLBACK = 'FALLBACK';
    private const NEVER = 'NEVER';

    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::__construct
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getConfig
     */
    public function shouldUseAlwaysAsValueForManualCheck()
    {
        $task = (new RequestedSupplementaryDocTextExtractionTaskBuilder())
            ->withManualCheckAlways()
            ->build();

        $expected = [
            'type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION,
            'config' => [
                'manual_check' => self::ALWAYS,
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($task));
        $this->assertJsonStringEqualsJsonString(json_encode($expected), (string) $task);
    }

    /**
     * @test
     * @covers ::withManualCheckFallback
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::__construct
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getConfig
     */
    public function shouldUseFallbackAsValueForManualCheck()
    {
        $task = (new RequestedSupplementaryDocTextExtractionTaskBuilder())
            ->withManualCheckFallback()
            ->build();

        $expected = [
            'type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION,
            'config' => [
                'manual_check' => self::FALLBACK,
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($task));
    }

    /**
     * @test
     * @covers ::withManualCheckNever
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::__construct
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getType
     * @covers \Yoti\DocScan\Session\Create\Task\RequestedSupplementaryDocTextExtractionTask::getConfig
     */
    public function shouldUseNeverAsValueForManualCheck()
    {
        $task = (new RequestedSupplementaryDocTextExtractionTaskBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION,
            'config' => [
                'manual_check' => self::NEVER,
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($task));
    }
}
