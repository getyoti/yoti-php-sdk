<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\DocScan\Session\Retrieve\ShareCodeMediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ShareCodeMediaResponse
 */
class ShareCodeMediaResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'media' => [
                'id' => 'some-media-id',
                'type' => 'IMAGE',
                'created' => '2026-01-14T10:00:00Z',
                'last_updated' => '2026-01-14T11:00:00Z',
            ],
        ];

        $result = new ShareCodeMediaResponse($input);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
        $this->assertEquals('some-media-id', $result->getMedia()->getId());
        $this->assertEquals('IMAGE', $result->getMedia()->getType());
        $this->assertNotNull($result->getMedia()->getCreated());
        $this->assertNotNull($result->getMedia()->getLastUpdated());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldHandleMissingMedia()
    {
        $result = new ShareCodeMediaResponse([]);

        $this->assertNull($result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldHandleEmptyMediaObject()
    {
        $input = [
            'media' => [],
        ];

        $result = new ShareCodeMediaResponse($input);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
        $this->assertNull($result->getMedia()->getId());
        $this->assertNull($result->getMedia()->getType());
    }
}
