<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse;
use Yoti\DocScan\Session\Retrieve\GeneratedTextDataCheckResponse;
use Yoti\DocScan\Session\Retrieve\TextExtractionTaskResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\TaskResponse
 */
class TaskResponseTest extends TestCase
{
    private const SOME_TYPE = 'someType';
    private const SOME_ID = 'someId';
    private const SOME_OTHER_ID = 'someOtherId';
    private const SOME_STATE = 'someState';
    private const SOME_CREATED = '2019-03-24T03:55:12Z';
    private const SOME_LAST_UPDATED = '2019-03-24T03:55:12Z';
    private const SOME_UNKNOWN_TYPE = 'someUnknownType';
    private const ID_DOCUMENT_TEXT_DATA_CHECK = 'ID_DOCUMENT_TEXT_DATA_CHECK';

    /**
     * @var TextExtractionTaskResponse
     */
    private $taskResponse;

    public function setup(): void
    {
         $this->taskResponse = new TextExtractionTaskResponse([
            'id' => self::SOME_ID,
            'type' => self::SOME_TYPE,
            'state' => self::SOME_STATE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
            'generated_checks' => [
                [
                    'id' => self::SOME_ID,
                    'type' => self::ID_DOCUMENT_TEXT_DATA_CHECK,
                ],
                [
                    'id' => self::SOME_OTHER_ID,
                    'type' => self::SOME_UNKNOWN_TYPE,
                ],
            ],
            'generated_media' => [
                [],
                [],
            ],
         ]);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::parseGeneratedChecks
     * @covers ::parseGeneratedMedia
     * @covers ::getType
     * @covers ::getId
     * @covers ::getState
     * @covers ::getCreated
     * @covers ::getLastUpdated
     * @covers ::getGeneratedMedia
     * @covers ::getGeneratedChecks
     */
    public function shouldMapPropertiesCorrectly()
    {
        $this->assertEquals(self::SOME_TYPE, $this->taskResponse->getType());
        $this->assertEquals(self::SOME_ID, $this->taskResponse->getId());
        $this->assertEquals(self::SOME_STATE, $this->taskResponse->getState());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_CREATED), $this->taskResponse->getCreated());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_LAST_UPDATED), $this->taskResponse->getLastUpdated());
        $this->assertCount(2, $this->taskResponse->getGeneratedChecks());
        $this->assertContainsOnlyInstancesOf(GeneratedCheckResponse::class, $this->taskResponse->getGeneratedChecks());
        $this->assertCount(2, $this->taskResponse->getGeneratedMedia());
    }

    /**
     * @test
     * @covers ::parseGeneratedChecks
     * @covers ::getGeneratedChecks
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::getId
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::getType
     */
    public function shouldReturnGeneratedTextDataChecks()
    {
        $generatedCheck = $this->taskResponse->getGeneratedChecks()[0];

        $this->assertInstanceOf(GeneratedTextDataCheckResponse::class, $generatedCheck);
        $this->assertEquals(self::SOME_ID, $generatedCheck->getId());
        $this->assertEquals(self::ID_DOCUMENT_TEXT_DATA_CHECK, $generatedCheck->getType());
    }

    /**
     * @test
     * @covers ::parseGeneratedChecks
     * @covers ::getGeneratedChecks
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::getId
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::getType
     */
    public function shouldReturnGeneratedCheckWithUnknownType()
    {
        $generatedCheck = $this->taskResponse->getGeneratedChecks()[1];

        $this->assertInstanceOf(GeneratedCheckResponse::class, $generatedCheck);
        $this->assertEquals(self::SOME_OTHER_ID, $generatedCheck->getId());
        $this->assertEquals(self::SOME_UNKNOWN_TYPE, $generatedCheck->getType());
    }

    /**
     * @test
     * @covers ::getGeneratedTextDataChecks
     * @covers ::filterGeneratedChecksByType
     */
    public function shouldFilterGeneratedTextDataChecks(): void
    {
        $this->assertCount(2, $this->taskResponse->getGeneratedChecks());
        $this->assertCount(1, $this->taskResponse->getGeneratedTextDataChecks());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenAllMissingValuesExceptType()
    {
        $result = new TextExtractionTaskResponse([]);

        $this->assertNull($result->getType());
        $this->assertNull($result->getId());
        $this->assertNull($result->getState());
        $this->assertNull($result->getCreated());
        $this->assertNull($result->getLastUpdated());
        $this->assertCount(0, $result->getGeneratedChecks());
        $this->assertCount(0, $result->getGeneratedMedia());
    }
}
