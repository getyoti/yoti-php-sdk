<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ApplicantProfileResourceResponse;
use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ApplicantProfileResourceResponse
 */
class ApplicantProfileResourceResponseTest extends TestCase
{
    private const SOME_ID = '3fa85f64-5717-4562-b3fc-2c963f66afa6';
    private const SOME_MEDIA_ID = 'some-media-id';
    private const SOME_MEDIA_TYPE = 'IMAGE';
    private const SOME_CREATED_AT = '2021-06-11T11:39:24Z';
    private const SOME_LAST_UPDATED = '2021-06-11T12:00:00Z';
    private const SOME_SOURCE_TYPE = 'END_USER';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMedia
     * @covers ::getCreatedAt
     * @covers ::getLastUpdated
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'source' => [
                'type' => self::SOME_SOURCE_TYPE,
            ],
            'media' => [
                'id' => self::SOME_MEDIA_ID,
                'type' => self::SOME_MEDIA_TYPE,
                'created' => self::SOME_CREATED_AT,
                'last_updated' => self::SOME_LAST_UPDATED,
            ],
            'created_at' => self::SOME_CREATED_AT,
            'last_updated' => self::SOME_LAST_UPDATED,
            'tasks' => [],
        ];

        $result = new ApplicantProfileResourceResponse($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertNotNull($result->getSource());
        $this->assertInstanceOf(MediaResponse::class, $result->getMedia());
        $this->assertEquals(self::SOME_MEDIA_ID, $result->getMedia()->getId());
        $this->assertEquals(self::SOME_MEDIA_TYPE, $result->getMedia()->getType());
        $this->assertEquals(
            DateTime::stringToDateTime(self::SOME_CREATED_AT),
            $result->getCreatedAt()
        );
        $this->assertEquals(
            DateTime::stringToDateTime(self::SOME_LAST_UPDATED),
            $result->getLastUpdated()
        );
        $this->assertCount(0, $result->getTasks());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ApplicantProfileResourceResponse([]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getMedia());
        $this->assertNull($result->getCreatedAt());
        $this->assertNull($result->getLastUpdated());
        $this->assertCount(0, $result->getTasks());
    }
}
