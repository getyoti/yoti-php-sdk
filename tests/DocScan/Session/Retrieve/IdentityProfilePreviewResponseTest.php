<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\IdentityProfilePreviewResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\IdentityProfilePreviewResponse
 */
class IdentityProfilePreviewResponseTest extends TestCase
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

        $result = new IdentityProfilePreviewResponse($data);

        $this->assertInstanceOf(IdentityProfilePreviewResponse::class, $result);
        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
    }
}
