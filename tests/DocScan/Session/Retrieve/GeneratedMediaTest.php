<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\GeneratedMedia;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\GeneratedMedia
 */
class GeneratedMediaTest extends TestCase
{

    private const SOME_ID = 'someId';
    private const SOME_TYPE = 'someType';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getType
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'id' => self::SOME_ID,
            'type' => self::SOME_TYPE,
        ];

        $result = new GeneratedMedia($input);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_TYPE, $result->getType());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new GeneratedMedia([]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getType());
    }
}
