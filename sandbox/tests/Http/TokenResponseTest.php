<?php

declare(strict_types=1);

namespace SandboxTest\Http;

use Psr\Http\Message\ResponseInterface;
use YotiSandbox\Http\TokenResponse;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Http\TokenResponse
 */
class TokenResponseTest extends TestCase
{
    /**
     * @covers ::getToken
     * @covers ::__construct
     * @covers ::processData
     * @covers ::checkResponseStatus
     */
    public function testGetToken()
    {
        $someToken = 'some-token';

        $someResponse = $this->createMock(ResponseInterface::class);
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
     */
    public function testGetTokenEmpty()
    {
        $this->expectException(\YotiSandbox\Exception\ResponseException::class, 'Token key is missing');

        $someResponse = $this->createMock(ResponseInterface::class);
        $someResponse->method('getStatusCode')->willReturn(201);
        $someResponse->method('getBody')->willReturn('{}');

        (new TokenResponse($someResponse))->getToken();
    }

    /**
     * @covers ::checkResponseStatus
     */
    public function testBadResponseStatusCode()
    {
        $this->expectException(\YotiSandbox\Exception\ResponseException::class, 'Server responded with 500');

        $someResponse = $this->createMock(ResponseInterface::class);
        $someResponse->method('getStatusCode')->willReturn(500);
        $someResponse->method('getBody')->willReturn('{}');

        (new TokenResponse($someResponse))->getToken();
    }

    /**
     * @covers ::processData
     */
    public function testInvalidJson()
    {
        $this->expectException(\Yoti\Exception\JsonException::class, 'Syntax error');

        $someResponse = $this->createMock(ResponseInterface::class);
        $someResponse->method('getStatusCode')->willReturn(201);
        $someResponse->method('getBody')->willReturn('invalid json');

        (new TokenResponse($someResponse))->getToken();
    }
}
