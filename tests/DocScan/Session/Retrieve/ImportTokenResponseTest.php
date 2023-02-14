<?php

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ImportTokenResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ImportTokenResponse
 */
class ImportTokenResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     * @covers ::getFailureReason
     */
    public function shouldCreatedCorrectly(): void
    {
        $data = [
            'media' => [
                'id' => 'SOME_ID',
                'type' => 'JSON',
                'created' => '2021-06-11T11:39:24Z',
                'last_updated' => '2021-06-11T11:39:24Z',
            ],
            'failure_reason' => 'SOME_REASON'
        ];

        $result = new ImportTokenResponse($data);

        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
        $this->assertInstanceOf(ImportTokenResponse::class, $result);
        $this->assertEquals('SOME_REASON', $result->getFailureReason());
    }
}
