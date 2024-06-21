<?php

namespace Yoti\Test\Identity;

use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Yoti\Identity\DigitalIdentityService;
use Yoti\Identity\Extension\Extension;
use Yoti\Identity\Policy\Policy;
use Yoti\Identity\Receipt;
use Yoti\Identity\ShareSessionCreated;
use Yoti\Identity\ShareSessionCreatedQrCode;
use Yoti\Identity\ShareSessionFetched;
use Yoti\Identity\ShareSessionFetchedQrCode;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;

/**
 * @coversDefaultClass \Yoti\Identity\DigitalIdentityService
 */
class DigitalIdentityServiceTest extends TestCase
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

        $identityService = $this->createMock(DigitalIdentityService::class);

        $result = $identityService->createShareSession($shareSessionRequest);

        $this->assertInstanceOf(ShareSessionCreated::class, $result);
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

        $identityService = $this->createMock(DigitalIdentityService::class);

        $result = $identityService->createShareQrCode(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionCreatedQrCode::class, $result);
    }

    /**
     * @covers ::fetchShareQrCode
     * @covers ::__construct
     */
    public function testShouldFetchShareQrCode()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'id',
            'expiry' => 'expiry',
            'policy' => 'policy',
            'extensions' => [['type' => 'type', 'content' => 'content']],
            'session' => ['id' => 'id', 'status' => 'status', 'expiry' => 'expiry'],
            'redirectUri' => 'redirectUri',
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $identityService = $this->createMock(DigitalIdentityService::class);

        $result = $identityService->fetchShareQrCode(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionFetchedQrCode::class, $result);
    }

    /**
     * @covers ::fetchShareSession
     * @covers ::__construct
     */
    public function testShouldFetchShareSession()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'SOME_ID',
            'status' => 'SOME_STATUS',
            'expiry' => 'SOME_EXPIRY',
            'created' => 'SOME_CREATED',
            'updated' => 'SOME_UPDATED',
            'qrCode' => ['id' => 'SOME_QRCODE_ID'],
            'receipt' => ['id' => 'SOME_RECEIPT_ID'],
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $identityService = $this->createMock(DigitalIdentityService::class);

        $result = $identityService->fetchShareSession(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionFetched::class, $result);
    }

    /**
     * @covers ::fetchShareReceipt
     * @covers ::__construct
     */
    public function testShouldFetchShareReceipt()
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getStatusCode')->willReturn(201);

        $identityService = $this->createMock(DigitalIdentityService::class);

        $result = $identityService->fetchShareReceipt(TestData::SOME_ID);

        $this->assertInstanceOf(Receipt::class, $result);
    }
}
