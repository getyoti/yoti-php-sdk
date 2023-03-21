<?php

namespace Yoti\Test;

use GuzzleHttp\Psr7;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\DigitalIdentityClient;
use Yoti\Identity\Policy\Policy;
use Yoti\Identity\ShareSessionCreated;
use Yoti\Identity\ShareSessionCreatedQrCode;
use Yoti\Identity\ShareSessionFetched;
use Yoti\Identity\ShareSessionFetchedQrCode;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\DigitalIdentityClient
 */
class DigitalIdentityClientTest extends TestCase
{
    /**
     * @covers ::createShareSession
     * @covers ::__construct
     */
    public function testCreateShareSession()
    {
        $policy = $this->createMock(Policy::class);
        $redirectUri = 'https://host/redirect/';

        $shareSessionRequest = (new ShareSessionRequestBuilder())
            ->withPolicy($policy)
            ->withRedirectUri($redirectUri)
            ->build();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'some_id',
            'status' => 'some_status',
            'expiry' => 'some_time',
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new DigitalIdentityClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->createShareSession($shareSessionRequest);

        $this->assertInstanceOf(ShareSessionCreated::class, $result);
    }

    /**
     * @covers ::createShareQrCode
     * @covers ::__construct
     */
    public function testCreateShareQrCode()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'id' => 'some_id',
            'uri' => 'some_uri',
        ])));

        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new DigitalIdentityClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->createShareQrCode(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionCreatedQrCode::class, $result);
    }

    /**
     * @covers ::fetchShareQrCode
     * @covers ::__construct
     */
    public function testFetchShareQrCode()
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

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new DigitalIdentityClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->fetchShareQrCode(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionFetchedQrCode::class, $result);
    }

    /**
     * @covers ::fetchShareSession
     * @covers ::__construct
     */
    public function testFetchShareSession()
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

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new DigitalIdentityClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->fetchShareSession(TestData::SOME_ID);

        $this->assertInstanceOf(ShareSessionFetched::class, $result);
    }
}
