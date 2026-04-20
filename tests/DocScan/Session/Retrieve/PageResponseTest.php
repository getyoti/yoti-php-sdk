<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\FrameResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\PageResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\PageResponse
 */
class PageResponseTest extends TestCase
{
    private const SOME_CAPTURE_METHOD = 'someCaptureMethod';

    /**
     * @covers ::__construct
     * @covers ::getCaptureMethod
     */
    public function testGetCaptureMethod()
    {
        $pageResponse = new PageResponse([
            'capture_method' => self::SOME_CAPTURE_METHOD,
        ]);

        $this->assertEquals(self::SOME_CAPTURE_METHOD, $pageResponse->getCaptureMethod());
    }

    /**
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function testGetMedia()
    {
        $pageResponse = new PageResponse([
            'media' => [],
        ]);

        $this->assertInstanceOf(MediaResponse::class, $pageResponse->getMedia());
    }

    /**
     * @covers ::__construct
     * @covers ::getFrames
     */
    public function testGetFrames()
    {
        $pageResponse = new PageResponse([
            'frames' => [[],[]],
        ]);

        $this->assertCount(2, $pageResponse->getFrames());
        $this->containsOnlyInstancesOf(FrameResponse::class, $pageResponse->getFrames());
    }

    /**
     * @covers ::__construct
     * @covers ::getExtractionImageIds
     */
    public function testGetExtractionImageIds()
    {
        $extractionImageIds = [
            '066a9372-0a52-4fe4-a026-866f8aee6fcb',
            '9b0c9c0a-ff30-41ed-815b-d95d63271d45',
        ];

        $pageResponse = new PageResponse([
            'extraction_image_ids' => $extractionImageIds,
        ]);

        $this->assertCount(2, $pageResponse->getExtractionImageIds());
        $this->assertEquals($extractionImageIds, $pageResponse->getExtractionImageIds());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new PageResponse([]);

        $this->assertNull($result->getCaptureMethod());
        $this->assertNull($result->getMedia());
        $this->assertEquals([], $result->getFrames());
        $this->assertEquals([], $result->getExtractionImageIds());
    }
}
