<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\DigitalIdShareErrorResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\DigitalIdShareErrorResponse
 */
class DigitalIdShareErrorResponseTest extends TestCase
{
    private const SOME_CODE = 'someCode';
    private const SOME_DESCRIPTION = 'someDescription';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCode
     * @covers ::getDescription
     */
    public function shouldBuildCorrectly()
    {
        $result = new DigitalIdShareErrorResponse([
            'code' => self::SOME_CODE,
            'description' => self::SOME_DESCRIPTION,
        ]);

        $this->assertEquals(self::SOME_CODE, $result->getCode());
        $this->assertEquals(self::SOME_DESCRIPTION, $result->getDescription());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new DigitalIdShareErrorResponse([]);

        $this->assertNull($result->getCode());
        $this->assertNull($result->getDescription());
    }
}
