<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\DocScan\DocScanClient;
use Yoti\DocScan\Session\Create\CreateSessionResult;
use Yoti\DocScan\Session\Create\SessionSpecification;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
use Yoti\Media\Media;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\DocScan\DocScanClient
 */
class DocScanClientTest extends TestCase
{

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldThrowExceptionForEmptySdkId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("SDK ID cannot be empty");

        new DocScanClient('', TestData::PEM_FILE);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createSession
     * @throws \Yoti\Exception\PemFileException
     * @throws \Yoti\DocScan\Exception\DocScanException
     */
    public function testCreateSession()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(file_get_contents(TestData::DOC_SCAN_SESSION_CREATION_RESPONSE));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $sessionSpecificationMock = $this->createMock(SessionSpecification::class);
        $sessionSpecificationMock->method('jsonSerialize')->willReturn(
            [
                'someKey' => 'someValue'
            ]
        );

        $this->assertInstanceOf(
            CreateSessionResult::class,
            $docScanClient->createSession($sessionSpecificationMock)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSession
     * @throws \Yoti\DocScan\Exception\DocScanException
     * @throws \Yoti\Exception\PemFileException
     */
    public function testGetSession()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(file_get_contents(TestData::DOC_SCAN_SESSION_RESPONSE));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            GetSessionResult::class,
            $docScanClient->getSession(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteSession
     * @throws \Yoti\DocScan\Exception\DocScanException
     * @throws \Yoti\Exception\PemFileException
     */
    public function testDeleteSessionDoesNotThrowException()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(204);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->deleteSession(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMediaContent
     * @throws \Yoti\DocScan\Exception\DocScanException
     * @throws \Yoti\Exception\PemFileException
     */
    public function testGetMedia()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(file_get_contents(TestData::DOC_SCAN_SESSION_RESPONSE));
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getHeader')->willReturn([ 'image/png' ]);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            Media::class,
            $docScanClient->getMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteMediaContent
     * @throws \Yoti\DocScan\Exception\DocScanException
     * @throws \Yoti\Exception\PemFileException
     */
    public function testDeleteMediaDoesNotThrowException()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(204);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->deleteMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID);
    }
}
