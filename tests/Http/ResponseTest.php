<?php

namespace YotiTest\Http;

use Yoti\Http\Response;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\Response
 */
class ResponseTest extends TestCase
{
    const SOME_BODY = 'some body';
    const SOME_STATUS_CODE = '200';

    /**
     * @covers ::__construct
     * @covers ::getBody
     */
    public function testGetBody()
    {
        $response = new Response(self::SOME_BODY, self::SOME_STATUS_CODE);

        $this->assertEquals(self::SOME_BODY, $response->getBody());
    }

    /**
     * @covers ::__construct
     * @covers ::getStatusCode
     */
    public function testGetStatusCode()
    {
        $response = new Response(self::SOME_BODY, self::SOME_STATUS_CODE);

        $this->assertEquals(self::SOME_STATUS_CODE, $response->getStatusCode());
        $this->assertTrue(is_int($response->getStatusCode()));
    }

    /**
     * @covers ::__construct
     * @covers ::getHeaders
     */
    public function testGetHeaders()
    {
        $expectedHeaders = [
            'Some-Header' => 'some value',
            'Some-Other-Header' => 'some other value',
        ];
        $response = new Response(self::SOME_BODY, self::SOME_STATUS_CODE, $expectedHeaders);

        $this->assertEquals($expectedHeaders, $response->getHeaders());
    }

    /**
     * @covers ::__construct
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage headers must be array of strings
     */
    public function testInvalidHeaders()
    {
        new Response(self::SOME_BODY, self::SOME_STATUS_CODE, [['invalid header value']]);
    }

    /**
     * @covers ::__construct
     */
    public function testEmptyHeaders()
    {
        $response = new Response(self::SOME_BODY, self::SOME_STATUS_CODE);
        $this->assertEquals([], $response->getHeaders());
    }
}
