<?php

declare(strict_types=1);

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
     * @covers ::validate
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
     * @covers ::validate
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
     * @covers ::validate
     */
    public function testDecodeExceptionSyntax()
    {
        $this->expectException(\Yoti\Exception\JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        Json::decode('some invalid json');
    }

    /**
     * @covers ::encode
     * @covers ::validate
     */
    public function testEncode()
    {
        $this->assertEquals(
            json_encode(self::SOME_JSON_DATA),
            Json::encode(self::SOME_JSON_DATA)
        );
    }
}
