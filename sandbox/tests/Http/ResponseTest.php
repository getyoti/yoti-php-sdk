<?php

namespace SandboxTest\Http;

use Yoti\Http\Response;
use YotiSandbox\Http\Response as TokenResponse;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Http\Response
 */
class ResponseTest extends TestCase
{
    /**
     * @covers ::getToken
     * @covers ::__construct
     * @covers ::processData
     * @covers ::checkJsonError
     * @covers ::checkResponseStatus
     */
    public function testGetToken()
    {
        $someToken = 'some-token';

        $someResponse = $this->createMock(Response::class);
        $someResponse->method('getStatusCode')->willReturn(201);
        $someResponse->method('getBody')->willReturn(json_encode([
            'token' => $someToken,
        ]));

        $response = new TokenResponse($someResponse);

        $this->assertEquals(
            $someToken,
            $response->getToken()
        );
    }

    /**
     * @covers ::getToken
     * @covers ::__construct
     * @covers ::processData
     * @covers ::checkJsonError
     *
     * @expectedException \YotiSandbox\Exception\ResponseException
     * @expectedExceptionMessage Token key is missing
     */
    public function testGetTokenEmpty()
    {
        $someResponse = $this->createMock(Response::class);
        $someResponse->method('getStatusCode')->willReturn(201);
        $someResponse->method('getBody')->willReturn('{}');

        (new TokenResponse($someResponse))->getToken();
    }

    /**
     * @covers ::checkResponseStatus
     *
     * @expectedException \YotiSandbox\Exception\ResponseException
     * @expectedExceptionMessage Server responded with 500
     */
    public function testBadResponseStatusCode()
    {
        $someResponse = $this->createMock(Response::class);
        $someResponse->method('getStatusCode')->willReturn(500);
        $someResponse->method('getBody')->willReturn('{}');

        (new TokenResponse($someResponse))->getToken();
    }

    /**
     * @covers ::checkJsonError
     *
     * @expectedException \YotiSandbox\Exception\ResponseException
     * @expectedExceptionMessage JSON response was invalid
     */
    public function testInvalidJson()
    {
        $someResponse = $this->createMock(Response::class);
        $someResponse->method('getStatusCode')->willReturn(201);
        $someResponse->method('getBody')->willReturn('invalid json');

        (new TokenResponse($someResponse))->getToken();
    }
}
