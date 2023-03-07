<?php

namespace Yoti\Test\Identity;

use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Yoti\Identity\Extension\Extension;
use Yoti\Identity\IdentityService;
use Yoti\Identity\Policy\Policy;
use Yoti\Identity\ShareSession;
use Yoti\Identity\ShareSessionQrCode;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;

/**
 * @coversDefaultClass \Yoti\Identity\IdentityService
 */
class IdentityServiceTest extends TestCase
{
    private const URI = 'uri';

    private Extension $extensionMock;
    private Policy $policyMock;

    public function setup(): void
    {
        $this->extensionMock = $this->createMock(Extension::class);
        $this->policyMock = $this->createMock(Policy::class);
    }

    /**
     * @covers ::createShareSession
     * @covers ::__construct
     */
    public function testShouldCreateShareSession()
    {
        $shareSessionRequest = (new ShareSessionRequestBuilder())
            ->withPolicy($this->policyMock)
            ->withRedirectUri(self::URI)
            ->withExtension($this->extensionMock)
            ->build();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'some_id',
            'status' => 'some_status',
            'expiry' => 'some_time',
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $identityService = $this->createMock(IdentityService::class);

        $result = $identityService->createShareSession($shareSessionRequest);

        $this->assertInstanceOf(ShareSession::class, $result);
    }

    /**
     * @covers ::createShareQrCode
     * @covers ::__construct
     */
    public function testShouldCreateShareQrCode()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'some_id',
            'uri' => 'some_uri',
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $identityService = $this->createMock(IdentityService::class);

        $result = $identityService->createShareQrCode(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionQrCode::class, $result);
    }
}
