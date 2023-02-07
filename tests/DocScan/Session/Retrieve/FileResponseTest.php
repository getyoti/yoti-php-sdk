<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\FileResponse;
use Yoti\IDV\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\FileResponse
 */
class FileResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $result = new FileResponse([
            'media' => [],
        ]);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldNotThrowExceptionWhenMediaMissing()
    {
        $result = new FileResponse([]);

        $this->assertNull($result->getMedia());
    }
}
