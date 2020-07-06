<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\PageResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\PageResponse
 */
class PageInfoTest extends TestCase
{

    private const SOME_CAPTURE_METHOD = 'someCaptureMethod';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCaptureMethod
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'capture_method' => self::SOME_CAPTURE_METHOD,
            'media' => [],
        ];

        $result = new PageResponse($input);

        $this->assertEquals(self::SOME_CAPTURE_METHOD, $result->getCaptureMethod());
        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
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
    }
}
