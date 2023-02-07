<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\FaceMapResponse;
use Yoti\IDV\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\FaceMapResponse
 */
class FacemapResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'media' => [ ]
        ];

        $result = new FaceMapResponse($input);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new FaceMapResponse([]);

        $this->assertNull($result->getMedia());
    }
}
