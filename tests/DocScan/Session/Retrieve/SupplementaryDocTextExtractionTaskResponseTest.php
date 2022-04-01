<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\GeneratedCheckResponse;
use Yoti\DocScan\Session\Retrieve\GeneratedSupplementaryDocTextDataCheckResponse;
use Yoti\DocScan\Session\Retrieve\SupplementaryDocTextExtractionTaskResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\SupplementaryDocTextExtractionTaskResponse
 */
class SupplementaryDocTextExtractionTaskResponseTest extends TestCase
{
    private const SOME_TYPE = 'someType';
    private const SOME_ID = 'someId';
    private const SOME_OTHER_ID = 'someOtherId';
    private const SOME_STATE = 'someState';
    private const SOME_CREATED = '2019-03-24T03:55:12Z';
    private const SOME_LAST_UPDATED = '2019-03-24T03:55:12Z';
    private const SOME_UNKNOWN_TYPE = 'someUnknownType';
    private const ID_DOCUMENT_TEXT_DATA_CHECK = 'ID_DOCUMENT_TEXT_DATA_CHECK';
    private const SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK = 'SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK';

    /**
     * @var SupplementaryDocTextExtractionTaskResponse
     */
    private $taskResponse;

    public function setup(): void
    {
         $this->taskResponse = new SupplementaryDocTextExtractionTaskResponse([
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
                    'type' => self::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK,
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
     * @covers ::getType
     */
    public function testGetType()
    {
        $this->assertEquals(self::SOME_TYPE, $this->taskResponse->getType());
    }

    /**
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertEquals(self::SOME_ID, $this->taskResponse->getId());
    }

    /**
     * @covers ::getState
     */
    public function testGetState()
    {
        $this->assertEquals(self::SOME_STATE, $this->taskResponse->getState());
    }

    /**
     * @covers ::getCreated
     */
    public function testGetCreated()
    {
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_CREATED), $this->taskResponse->getCreated());
    }

    /**
     * @covers ::getLastUpdated
     */
    public function testGetLastUpdated()
    {
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_LAST_UPDATED), $this->taskResponse->getLastUpdated());
    }

    /**
     * @covers ::getGeneratedMedia
     */
    public function testGetGeneratedMedia()
    {
        $this->assertCount(2, $this->taskResponse->getGeneratedMedia());
    }

    /**
     * @covers ::getGeneratedChecks
     */
    public function testGetGeneratedChecks()
    {
        $this->assertCount(3, $this->taskResponse->getGeneratedChecks());
        $this->assertContainsOnlyInstancesOf(
            GeneratedCheckResponse::class,
            $this->taskResponse->getGeneratedChecks()
        );
    }

    /**
     * @covers ::getGeneratedTextDataChecks
     */
    public function testGetGeneratedTextDataChecks()
    {
        $this->assertCount(1, $this->taskResponse->getGeneratedTextDataChecks());
        $this->assertContainsOnlyInstancesOf(
            GeneratedSupplementaryDocTextDataCheckResponse::class,
            $this->taskResponse->getGeneratedTextDataChecks()
        );
    }
}
