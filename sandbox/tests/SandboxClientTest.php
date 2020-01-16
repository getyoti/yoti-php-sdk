<?php

declare(strict_types=1);

namespace SandboxTest;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Payload;
use Yoti\Util\Config;
use YotiSandbox\Http\SandboxPathManager;
use YotiSandbox\Http\TokenRequest;
use YotiSandbox\SandboxClient;
use YotiTest\TestCase;
use YotiTest\TestData;

/**
 * @coversDefaultClass \YotiSandbox\SandboxClient
 */
class SandboxClientTest extends TestCase
{
    /**
     * @covers ::getToken
     * @covers ::sendRequest
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

        $mockSandboxPathManager = $this->createMock(SandboxPathManager::class);
        $mockSandboxPathManager->method('getTokenApiPath')->willReturn('/some-token-api-path');

        $sandboxClient = new SandboxClient(
            TestData::SDK_ID,
            TestData::PEM_FILE,
            $mockSandboxPathManager,
            [
                Config::HTTP_CLIENT => $mockHttpClient,
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
