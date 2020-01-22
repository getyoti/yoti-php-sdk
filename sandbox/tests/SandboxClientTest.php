<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Payload;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\SandboxClient;
use Yoti\Util\Config;
use YotiTest\TestCase;
use YotiTest\TestData;

/**
 * @coversDefaultClass \Yoti\Sandbox\SandboxClient
 */
class SandboxClientTest extends TestCase
{
    /**
     * @covers ::getToken
     * @covers ::__construct
     */
    public function testGetToken()
    {
        $expectedConnectToken = file_get_contents(TestData::YOTI_CONNECT_TOKEN);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getBody')
            ->willReturn(json_encode([
                'token' => $expectedConnectToken
            ]));
        $mockResponse
            ->method('getStatusCode')
            ->willReturn(201);

        $mockHttpClient = $this->createMock(ClientInterface::class);
        $mockHttpClient->method('sendRequest')->willReturn($mockResponse);

        $sandboxClient = new SandboxClient(
            TestData::SDK_ID,
            TestData::PEM_FILE,
            [
                Config::HTTP_CLIENT => $mockHttpClient,
                Config::SANDBOX_API_URL => '/some-sandbox-path',
            ]
        );

        $mockTokenRequest = $this->createMock(TokenRequest::class);
        $mockTokenRequest
            ->method('getPayload')
            ->willReturn($this->createMock(Payload::class));

        $token = $sandboxClient->getToken($mockTokenRequest);

        $this->assertEquals($expectedConnectToken, $token);
    }
}
