<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\FrameResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\FrameResponse
 */
class FrameResponseTest extends TestCase
{

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'media' => []
        ];

        $result = new FrameResponse($input);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new FrameResponse([]);

        $this->assertNull($result->getMedia());
    }
}
