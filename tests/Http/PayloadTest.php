<?php

declare(strict_types=1);

namespace Yoti\Test\Http;

use GuzzleHttp\Psr7;
use Psr\Http\Message\StreamInterface;
use Yoti\Http\Payload;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Payload
 */
class PayloadTest extends TestCase
{
    private const SOME_STRING = 'some string';

    /**
     * Test getting Payload data as a stream.
     *
     * @covers ::toStream
     */
    public function testToStream()
    {
        $payload = Payload::fromString(self::SOME_STRING);
        $stream = $payload->toStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertEquals(self::SOME_STRING, (string) $stream);
    }

    /**
     * Test getting Payload data as a string.
     *
     * @covers ::__toString
     */
    public function testToString()
    {
        $payload = Payload::fromString(self::SOME_STRING);
        $this->assertEquals(self::SOME_STRING, (string) $payload);
    }

    /**
     * Test getting Payload data as a Base64 string.
     *
     * @covers ::toBase64
     */
    public function testToBase64()
    {
        $somePayloadStringBase64 = base64_encode(self::SOME_STRING);
        $payload = Payload::fromString(self::SOME_STRING);

        $this->assertEquals($somePayloadStringBase64, $payload->toBase64());
    }

    /**
     * @covers ::__construct
     * @covers ::fromJsonData
     */
    public function testFromJsonData()
    {
        $somePayloadJsonData = ['some' => 'data'];
        $payload = Payload::fromJsonData($somePayloadJsonData);

        $this->assertEquals(json_encode($somePayloadJsonData), (string) $payload);
    }

    /**
     * @covers ::__construct
     * @covers ::fromString
     */
    public function testFromString()
    {
        $payload = Payload::fromString(self::SOME_STRING);

        $this->assertEquals(self::SOME_STRING, (string) $payload);
    }

    /**
     * @covers ::__construct
     * @covers ::fromStream
     */
    public function testFromStream()
    {
        $somePayloadStream = Psr7\Utils::streamFor(self::SOME_STRING);
        $payload = Payload::fromStream($somePayloadStream);

        $this->assertEquals($somePayloadStream, $payload->toStream());
    }
}
