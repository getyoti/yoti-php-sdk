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
     */
    public function testDecodeExceptionSyntax()
    {
        $this->expectException(\Yoti\Exception\JsonException::class, 'Syntax error');

        Json::decode('some invalid json');
    }
}
