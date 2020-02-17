<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Payload;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\SandboxClient;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\Sandbox\SandboxClient
 */
class SandboxClientTest extends TestCase
{
    /**
     * @covers ::setupSharingProfile
     * @covers ::__construct
     */
    public function testSetupSharingProfile()
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
            ]
        );

        $mockTokenRequest = $this->createMock(TokenRequest::class);
        $mockTokenRequest
            ->method('getPayload')
            ->willReturn($this->createMock(Payload::class));

        $token = $sandboxClient->setupSharingProfile($mockTokenRequest);

        $this->assertEquals($expectedConnectToken, $token);
    }
}
