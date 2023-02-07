<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\FrameResponse;
use Yoti\IDV\Session\Retrieve\MediaResponse;
use Yoti\IDV\Session\Retrieve\PageResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\PageResponse
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
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new PageResponse([]);

        $this->assertNull($result->getCaptureMethod());
        $this->assertNull($result->getMedia());
        $this->assertEquals([], $result->getFrames());
    }
}
