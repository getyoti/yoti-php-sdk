<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ResourceResponse;
use Yoti\DocScan\Session\Retrieve\TaskResponse;
use Yoti\DocScan\Session\Retrieve\TextExtractionTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ResourceResponse
 */
class ResourceResponseTest extends TestCase
{

    private const SOME_ID = 'someId';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getTasks
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'tasks' => [
                ['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION'],
            ],
        ];

        $result = new ResourceResponse($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertCount(1, $result->getTasks());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createTaskFromArray
     */
    public function shouldHandleTextDataExtractionTask()
    {
        $input = [
            'tasks' => [
                ['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION'],
            ],
        ];

        $result = new ResourceResponse($input);

        $this->assertCount(1, $result->getTasks());
        $this->assertInstanceOf(TextExtractionTaskResponse::class, $result->getTasks()[0]);
    }

    /**
     * @test
     * @covers ::getTextExtractionTasks
     * @covers ::filterTasksByType
     */
    public function shouldFilterTextExtractionTasks(): void
    {
        $input = [
            'tasks' => [
                ['type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION'],
                ['type' => 'someUnknownType'],
            ],
        ];

        $result = new ResourceResponse($input);

        $this->assertCount(2, $result->getTasks());
        $this->assertCount(1, $result->getTextExtractionTasks());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createTaskFromArray
     */
    public function shouldIgnoreUnknownTypeOfTask()
    {
        $input = [
            'tasks' => [
                ['type' => 'someUnknownType'],
            ],
        ];

        $result = new ResourceResponse($input);

        $this->assertCount(1, $result->getTasks());
        $this->assertInstanceOf(TaskResponse::class, $result->getTasks()[0]);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ResourceResponse([]);

        $this->assertNull($result->getId());
        $this->assertCount(0, $result->getTasks());
    }
}
