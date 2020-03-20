<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Yoti\Test\TestCase;
use Yoti\Util\Json;

/**
 * @coversDefaultClass \Yoti\Util\Json
 */
class JsonTest extends TestCase
{
    private const SOME_JSON_DATA = ['some' => 'json'];
    private const SOME_JSON_DATA_WITH_NULL = [
        'some' => 'json',
        'other_value' => null,
    ];

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

    /**
     * @covers ::withoutNullValues
     */
    public function testWithoutNullValues()
    {
        $withoutNull = Json::withoutNullValues(self::SOME_JSON_DATA_WITH_NULL);

        $this->assertArrayNotHasKey('other_key', $withoutNull);
    }
}
