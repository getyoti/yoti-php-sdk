<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Response;

use Psr\Http\Message\ResponseInterface;
use Yoti\Sandbox\Profile\Response\TokenResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Response\TokenResponse
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
        $this->expectException(\Yoti\Sandbox\Exception\ResponseException::class, 'Token key is missing');

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
        $this->expectException(\Yoti\Sandbox\Exception\ResponseException::class, 'Server responded with 500');

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
