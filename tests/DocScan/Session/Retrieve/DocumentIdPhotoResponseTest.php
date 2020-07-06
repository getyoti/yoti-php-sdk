<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\DocumentIdPhotoResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\DocumentIdPhotoResponse
 */
class DocumentIdPhotoResponseTest extends TestCase
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

        $result = new DocumentIdPhotoResponse($input);

        $this->assertNotNull($result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldNotThrowExceptionWhenMediaMissing()
    {
        $input = [];

        $result = new DocumentIdPhotoResponse($input);

        $this->assertNull($result->getMedia());
    }
}
