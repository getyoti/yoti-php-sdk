<?php

namespace YotiTest\Util;

use Yoti\Util\Json;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\Json
 */
class JsonTest extends TestCase
{
    const SOME_JSON_DATA = ['some' => 'json'];

    /**
     * @covers ::decode
     */
    public function testDecode()
    {
        $this->assertEquals(
            self::SOME_JSON_DATA,
            Json::decode(json_encode(self::SOME_JSON_DATA))
        );
    }

    /**
     * @covers ::decode
     */
    public function testDecodeToObject()
    {
        $this->assertEquals(
            (object) self::SOME_JSON_DATA,
            Json::decode(json_encode(self::SOME_JSON_DATA), false)
        );
    }

    /**
     * @covers ::decode
     *
     * @expectedException \Yoti\Exception\JsonException
     * @expectedExceptionMessage Syntax error
     */
    public function testDecodeExceptionSyntax()
    {
        Json::decode('some invalid json');
    }
}
