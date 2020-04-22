<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Exception\UnknownCheckException;
use Yoti\DocScan\Session\Retrieve\AuthenticityCheckResponse;
use Yoti\DocScan\Session\Retrieve\CheckResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\CheckResponse
 */
class CheckResponseTest extends TestCase
{
    private const SOME_TYPE = 'someType';
    private const SOME_ID = 'someId';
    private const SOME_STATE = 'someState';
    private const SOME_CREATED = '2019-12-02T12:00:00.123Z';
    private const SOME_LAST_UPDATED = '2019-12-02T12:00:00.123Z';
    private const SOME_RESOURCES_USED = [ 'firstResource', 'secondResource' ];
    private const SOME_GENERATED_MEDIA = [
        [ 'someKey' => 'someValue' ],
        [ 'someOtherKey' => 'someOtherValue' ],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     * @covers ::getId
     * @covers ::getState
     * @covers ::getCreated
     * @covers ::getLastUpdated
     * @covers ::getResourcesUsed
     * @covers ::getGeneratedMedia
     * @throws \Yoti\Exception\DateTimeException
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
            'resources_used' => self::SOME_RESOURCES_USED,
            'generated_media' => self::SOME_GENERATED_MEDIA,
            'report' => [],
            'type' => self::SOME_TYPE,
        ];

        $result = new CheckResponse($input);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_STATE, $result->getState());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_CREATED), $result->getCreated());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_LAST_UPDATED), $result->getLastUpdated());
        $this->assertNotNull($result->getReport());

        $this->assertCount(2, $result->getResourcesUsed());
        $this->assertCount(2, $result->getGeneratedMedia());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getReport
     */
    public function shouldParseReport()
    {
        $input = [
            'type' => self::SOME_TYPE,
            'report' => [ ] // Key just needs to exist for test
        ];

        $result = new AuthenticityCheckResponse($input);

        $this->assertInstanceOf(AuthenticityCheckResponse::class, $result);
        $this->assertNotNull($result->getReport());
    }

    /**
     * @test
     * @covers ::getResourcesUsed
     * @covers ::getGeneratedMedia
     * @throws UnknownCheckException
     */
    public function resourcesUsedAndGeneratedMediaShouldDefaultToEmptyList()
    {
        $input = [
            'type' => self::SOME_TYPE,
        ];

        $result = new CheckResponse($input);

        $this->assertCount(0, $result->getResourcesUsed());
        $this->assertCount(0, $result->getGeneratedMedia());
    }
}
