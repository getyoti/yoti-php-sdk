<?php

namespace SandboxTest;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\ActivityDetails;
use Yoti\Http\Payload;
use YotiSandbox\Http\SandboxPathManager;
use YotiSandbox\Http\TokenRequest;
use YotiSandbox\SandboxClient;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\SandboxClient
 */
class SandboxClientTest extends TestCase
{
    /**
     * @covers ::getToken
     * @covers ::sendRequest
     * @covers ::__construct
     * @covers ::includePemWrapper
     */
    public function testGetToken()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getBody')
            ->willReturn(json_encode([
                'token' => YOTI_CONNECT_TOKEN
            ]));
        $mockResponse
            ->method('getStatusCode')
            ->willReturn(201);

        $mockHttpClient = $this->createMock(ClientInterface::class);
        $mockHttpClient->method('sendRequest')->willReturn($mockResponse);

        $mockSandboxPathManager = $this->createMock(SandboxPathManager::class);
        $mockSandboxPathManager->method('getTokenApiPath')->willReturn('/some-api-path');

        $sandboxClient = new SandboxClient(
            SDK_ID,
            file_get_contents(PEM_FILE),
            $mockSandboxPathManager,
            'PHP',
            $mockHttpClient
        );

        $mockTokenRequest = $this->createMock(TokenRequest::class);
        $mockTokenRequest
            ->method('getPayload')
            ->willReturn($this->createMock(Payload::class));

        $token = $sandboxClient->getToken($mockTokenRequest);

        $this->assertEquals(YOTI_CONNECT_TOKEN, $token);
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::includePemWrapper
     * @covers ::__construct
     */
    public function testGetActivityDetails()
    {
        $sandboxClient = new SandboxClient(
            SDK_ID,
            file_get_contents(PEM_FILE),
            $this->createMockPathManager(),
            'PHP',
            $this->createMockHttpClientForActivityDetails()
        );
        $activityDetails = $sandboxClient->getActivityDetails(YOTI_CONNECT_TOKEN);

        $this->assertInstanceOf(ActivityDetails::class, $activityDetails);
    }

    /**
     * @covers ::includePemWrapper
     * @covers ::__construct
     */
    public function testGetActivityDetailsWithUnwrappedKey()
    {
        $pemLines = explode("\n", trim(file_get_contents(PEM_FILE)));
        array_shift($pemLines);
        array_pop($pemLines);
        $pemWithoutWrapper = implode("\n", $pemLines);

        $sandboxClient = new SandboxClient(
            SDK_ID,
            $pemWithoutWrapper,
            $this->createMockPathManager(),
            'PHP',
            $this->createMockHttpClientForActivityDetails()
        );

        $activityDetails = $sandboxClient->getActivityDetails(YOTI_CONNECT_TOKEN);

        $this->assertInstanceOf(ActivityDetails::class, $activityDetails);
    }

    /**
     * @return \YotiSandbox\Http\SandboxPathManager
     */
    private function createMockPathManager()
    {
        $mockSandboxPathManager = $this->createMock(SandboxPathManager::class);
        $mockSandboxPathManager->method('getProfileApiPath')->willReturn('/some-profile-api-path');
        $mockSandboxPathManager->method('getTokenApiPath')->willReturn('/some-token-api-path');

        return $mockSandboxPathManager;
    }

    /**
     * @return \Psr\Http\Client\ClientInterface
     */
    private function createMockHttpClientForActivityDetails()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getBody')
            ->willReturn(file_get_contents(RECEIPT_JSON));
        $mockResponse
            ->method('getStatusCode')
            ->willReturn(200);

        $mockHttpClient = $this->createMock(ClientInterface::class);
        $mockHttpClient->method('sendRequest')->willReturn($mockResponse);

        return $mockHttpClient;
    }
}
