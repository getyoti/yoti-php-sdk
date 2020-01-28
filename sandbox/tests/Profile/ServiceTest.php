<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Http\Payload;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Sandbox\Profile\Service;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Service
 */
class ServiceTest extends TestCase
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

        $service = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $mockHttpClient,
            ])
        );

        $mockTokenRequest = $this->createMock(TokenRequest::class);
        $mockTokenRequest
            ->method('getPayload')
            ->willReturn($this->createMock(Payload::class));

        $token = $service->setupSharingProfile($mockTokenRequest);

        $this->assertEquals($expectedConnectToken, $token);
    }
}
