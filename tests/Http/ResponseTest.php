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
     * @var \Yoti\Http\Response
     */
    public $response;

    public function setup()
    {
        $this->response = new Response(self::SOME_BODY, self::SOME_STATUS_CODE);
    }

    /**
     * @covers ::__construct
     * @covers ::getBody
     */
    public function testGetBody()
    {
        $this->assertEquals(self::SOME_BODY, $this->response->getBody());
    }

    /**
     * @covers ::__construct
     * @covers ::getStatusCode
     */
    public function testGetStatusCode()
    {
        $this->assertEquals(self::SOME_STATUS_CODE, $this->response->getStatusCode());
        $this->assertTrue(is_int($this->response->getStatusCode()));
    }
}
