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

    /**
     * @covers ::convertFromLatin1ToUtf8Recursively
     */
    public function testConvertFromLatin1ToUtf8Recursively()
    {
        $latin1String = mb_convert_encoding('éàê', 'ISO-8859-1', 'UTF-8');
        $latin1Array = [
            mb_convert_encoding('éàê', 'ISO-8859-1', 'UTF-8'),
            mb_convert_encoding('çî', 'ISO-8859-1', 'UTF-8')
        ];
        $nestedLatin1Array = [
            mb_convert_encoding('éàê', 'ISO-8859-1', 'UTF-8'),
            [
                mb_convert_encoding('çî', 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding('üñ', 'ISO-8859-1', 'UTF-8')
            ]
        ];

        $latin1Object = new \stdClass();
        $latin1Object->property1 = mb_convert_encoding('éàê', 'ISO-8859-1', 'UTF-8');
        $latin1Object->property2 = mb_convert_encoding('çî', 'ISO-8859-1', 'UTF-8');

        $nestedLatin1Object = new \stdClass();
        $nestedLatin1Object->property = mb_convert_encoding('çî', 'ISO-8859-1', 'UTF-8');
        $latin1ObjectWithNestedObject = new \stdClass();
        $latin1ObjectWithNestedObject->property1 = mb_convert_encoding('éàê', 'ISO-8859-1', 'UTF-8');
        $latin1ObjectWithNestedObject->property2 = $nestedLatin1Object;

        $this->assertSame('éàê', Json::convertFromLatin1ToUtf8Recursively($latin1String));
        $this->assertSame(['éàê', 'çî'], Json::convertFromLatin1ToUtf8Recursively($latin1Array));
        $this->assertSame(['éàê', ['çî', 'üñ']], Json::convertFromLatin1ToUtf8Recursively($nestedLatin1Array));

        $utf8Object = Json::convertFromLatin1ToUtf8Recursively($latin1Object);
        $this->assertSame('éàê', $utf8Object->property1);
        $this->assertSame('çî', $utf8Object->property2);

        $utf8NestedObject = Json::convertFromLatin1ToUtf8Recursively($latin1ObjectWithNestedObject);
        $this->assertSame('éàê', $utf8NestedObject->property1);
        $this->assertSame('çî', $utf8NestedObject->property2->property);
    }
}
