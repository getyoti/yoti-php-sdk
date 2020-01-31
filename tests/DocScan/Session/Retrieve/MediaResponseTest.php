<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\MediaResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\MediaResponse
 */
class MediaResponseTest extends TestCase
{

    private const SOME_ID = 'someId';
    private const SOME_TYPE = 'someType';
    private const SOME_CREATED = '2019-03-24T03:55:12Z';
    private const SOME_LAST_UPDATED = '2019-03-24T03:55:12Z';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getType
     * @covers ::getCreated
     * @covers ::getLastUpdated
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'type' => self::SOME_TYPE,
            'created' => self::SOME_CREATED,
            'last_updated' => self::SOME_LAST_UPDATED,
        ];

        $result = new MediaResponse($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_CREATED), $result->getCreated());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_LAST_UPDATED), $result->getLastUpdated());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenValuesMissing()
    {
        $result = new MediaResponse([]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getType());
        $this->assertNull($result->getCreated());
        $this->assertNull($result->getLastUpdated());
    }
}
