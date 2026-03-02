<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\TaskResponse;
use Yoti\DocScan\Session\Retrieve\VerifyShareCodeTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\VerifyShareCodeTaskResponse
 */
class VerifyShareCodeTaskResponseTest extends TestCase
{
    private const VERIFY_SHARE_CODE_TASK = 'VERIFY_SHARE_CODE_TASK';

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'type' => self::VERIFY_SHARE_CODE_TASK,
            'id' => 'some-task-id',
            'state' => 'DONE',
            'created' => '2026-01-14T10:00:00Z',
            'last_updated' => '2026-01-14T11:00:00Z',
            'generated_media' => [
                ['id' => 'media-1', 'type' => 'PDF'],
                ['id' => 'media-2', 'type' => 'IMAGE'],
            ],
        ];

        $result = new VerifyShareCodeTaskResponse($input);

        $this->assertInstanceOf(TaskResponse::class, $result);
        $this->assertEquals(self::VERIFY_SHARE_CODE_TASK, $result->getType());
        $this->assertEquals('some-task-id', $result->getId());
        $this->assertEquals('DONE', $result->getState());
        $this->assertNotNull($result->getCreated());
        $this->assertNotNull($result->getLastUpdated());
        $this->assertCount(2, $result->getGeneratedMedia());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new VerifyShareCodeTaskResponse([]);

        $this->assertNull($result->getType());
        $this->assertNull($result->getId());
        $this->assertNull($result->getState());
        $this->assertNull($result->getCreated());
        $this->assertNull($result->getLastUpdated());
        $this->assertCount(0, $result->getGeneratedMedia());
    }
}
