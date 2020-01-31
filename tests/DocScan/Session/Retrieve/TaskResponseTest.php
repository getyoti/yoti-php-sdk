<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse;
use Yoti\DocScan\Session\Retrieve\GeneratedTextDataCheckResponse;
use Yoti\DocScan\Session\Retrieve\TaskResponse;
use Yoti\DocScan\Session\Retrieve\TextExtractionTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\TaskResponse
 */
class TaskResponseTest extends TestCase
{

    private const SOME_ID = 'someId';
    private const SOME_STATE = 'someState';
    private const SOME_CREATED = '2019-03-24T03:55:12Z';
    private const SOME_LAST_UPDATED = '2019-03-24T03:55:12Z';
    private const SOME_GENERATED_CHECKS = [
        [ 'type' => 'ID_DOCUMENT_TEXT_DATA_CHECK' ],
    ];
    private const SOME_GENERATED_MEDIA = [
        [ 'someKey' => 'someValue' ],
        [ 'someOtherKey' => 'someOtherValue' ],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::parseGeneratedChecks
     * @covers ::parseGeneratedMedia
     * @covers ::getId
     * @covers ::getState
     * @covers ::getCreated
     * @covers ::getLastUpdated
     * @covers ::getGeneratedMedia
     * @covers ::getGeneratedChecks
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
            'generated_checks' => self::SOME_GENERATED_CHECKS,
            'generated_media' => self::SOME_GENERATED_MEDIA,
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
        ];

        $result = new TextExtractionTaskResponse($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_STATE, $result->getState());
        $this->assertEquals(self::SOME_CREATED, $result->getCreated());
        $this->assertEquals(self::SOME_LAST_UPDATED, $result->getLastUpdated());

        $this->assertCount(1, $result->getGeneratedChecks());
        $this->assertInstanceOf(GeneratedTextDataCheckResponse::class, $result->getGeneratedChecks()[0]);
        $this->assertCount(2, $result->getGeneratedMedia());
    }

    /**
     * @test
     * @covers ::parseGeneratedChecks
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse::getId
     */
    public function shouldReturnGeneratedCheckWithUnknownType()
    {
        $input = [
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
            'generated_checks' => [
                [
                    'id' => 'someId',
                    'type' => 'someUnknownType',
                ],
            ],
            'generated_media' => self::SOME_GENERATED_MEDIA,
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
        ];

        $result = new TextExtractionTaskResponse($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_STATE, $result->getState());
        $this->assertEquals(self::SOME_CREATED, $result->getCreated());
        $this->assertEquals(self::SOME_LAST_UPDATED, $result->getLastUpdated());

        $this->assertCount(2, $result->getGeneratedMedia());

        $this->assertCount(1, $result->getGeneratedChecks());
        $this->assertEquals('someId', $result->getGeneratedChecks()[0]->getId());
        $this->assertInstanceOf(GeneratedCheckResponse::class, $result->getGeneratedChecks()[0]);
    }

    /**
     * @test
     * @covers ::getGeneratedTextDataChecks
     * @covers ::filterGeneratedChecksByType
     */
    public function shouldFilterGeneratedTextDataChecks(): void
    {
        $input = [
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
            'generated_checks' => [
                [
                    'type' => 'someUnknownType',
                ],
                [
                    'type' => 'ID_DOCUMENT_TEXT_DATA_CHECK',
                ]
            ],
            'generated_media' => self::SOME_GENERATED_MEDIA,
            'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION',
        ];

        $result = new TaskResponse($input);

        $this->assertCount(2, $result->getGeneratedChecks());
        $this->assertCount(1, $result->getGeneratedTextDataChecks());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenAllMissingValuesExceptType()
    {
        $result = new TextExtractionTaskResponse([ 'type' => 'ID_DOCUMENT_TEXT_DATA_EXTRACTION' ]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getState());
        $this->assertNull($result->getCreated());
        $this->assertNull($result->getLastUpdated());
        $this->assertCount(0, $result->getGeneratedChecks());
        $this->assertCount(0, $result->getGeneratedMedia());
    }
}
