<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\AdvancedIdentityProfilePreviewResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\AdvancedIdentityProfilePreviewResponse
 */
class AdvancedIdentityProfilePreviewResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldCreatedCorrectly(): void
    {
        $data = [
            'media' => [
                    'id' => 'SOME_ID',
                    'type' => 'JSON',
                    'created' => '2021-06-11T11:39:24Z',
                    'last_updated' => '2021-06-11T11:39:24Z',
                ]
        ];

        $result = new AdvancedIdentityProfilePreviewResponse($data);

        $this->assertInstanceOf(AdvancedIdentityProfilePreviewResponse::class, $result);
        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     */
    public function shouldHandleMissingMedia(): void
    {
        $result = new AdvancedIdentityProfilePreviewResponse([]);

        $this->assertNull($result->getMedia());
    }
}
