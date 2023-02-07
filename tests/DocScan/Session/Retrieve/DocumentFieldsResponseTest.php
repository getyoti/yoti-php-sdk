<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Session\Retrieve;

use Yoti\IDV\Session\Retrieve\DocumentFieldsResponse;
use Yoti\IDV\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Retrieve\DocumentFieldsResponse
 */
class DocumentFieldsResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'media' => [ ], // Key just needs to exist
        ];

        $result = new DocumentFieldsResponse($input);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldNotThrowExceptionWhenMediaMissing()
    {
        $input = [];

        $result = new DocumentFieldsResponse($input);

        $this->assertNull($result->getMedia());
    }
}
