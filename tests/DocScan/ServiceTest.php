<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan;

use GuzzleHttp\Psr7;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\DocScan\Exception\DocScanException;
use Yoti\DocScan\Service;
use Yoti\DocScan\Session\Create\CreateSessionResult;
use Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload;
use Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayload;
use Yoti\DocScan\Session\Create\SessionSpecification;
use Yoti\DocScan\Session\Instructions\Instructions;
use Yoti\DocScan\Session\Retrieve\Configuration\SessionConfigurationResponse;
use Yoti\DocScan\Session\Retrieve\CreateFaceCaptureResourceResponse;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
use Yoti\DocScan\Session\Retrieve\Instructions\ContactProfileResponse;
use Yoti\DocScan\Support\SupportedDocumentsResponse;
use Yoti\Exception\PemFileException;
use Yoti\Media\Media;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\DocScan\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::createSession
     * @covers ::assertResponseIsSuccess
     */
    public function createSessionShouldReturnCreateSessionResultOnSuccessfulCall()
    {
        $sessionSpecificationMock = $this->createMock(SessionSpecification::class);
        $sessionSpecificationMock->method('jsonSerialize')->willReturn(
            (object)[
                'someKey' => 'someValue',
                'someOtherKey' => 'someOtherValue',
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(201, file_get_contents(TestData::DOC_SCAN_SESSION_RESPONSE)));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            CreateSessionResult::class,
            $docScanService->createSession($sessionSpecificationMock)
        );
    }

    /**
     * @param int $statusCode
     * @param string $body
     * @param array<string, string[]> $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createResponse(int $statusCode, string $body = '{}', array $headers = []): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor($body));
        $response->method('getStatusCode')->willReturn($statusCode);
        foreach ($headers as $header => $value) {
            $params[] = [$header, $value];
        }
        if (isset($params)) {
            $response->method('getHeader')->will($this->returnValueMap($params));
        }
        return $response;
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createSession
     * @covers ::assertResponseIsSuccess
     */
    public function createSessionShouldThrowExceptionOnErrorHttpCode()
    {
        $sessionSpecificationMock = $this->createMock(SessionSpecification::class);
        $sessionSpecificationMock->method('jsonSerialize')->willReturn(
            (object)[
                'someKey' => 'someValue',
                'someOtherKey' => 'someOtherValue',
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(400));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 400");

        $docScanService->createSession($sessionSpecificationMock);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::retrieveSession
     * @covers ::assertResponseIsSuccess
     */
    public function retrieveSessionShouldReturnDocScanSessionOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, file_get_contents(TestData::DOC_SCAN_SESSION_CREATION_RESPONSE)));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            GetSessionResult::class,
            $docScanService->retrieveSession(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::retrieveSession
     * @covers ::assertResponseIsSuccess
     */
    public function retrieveSessionShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->retrieveSession(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteSession
     * @covers ::assertResponseIsSuccess
     */
    public function deleteSessionShouldNotThrowExceptionOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('DELETE', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, file_get_contents(TestData::DOC_SCAN_SESSION_CREATION_RESPONSE)));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->deleteSession(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteSession
     * @covers ::assertResponseIsSuccess
     */
    public function deleteSessionThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('DELETE', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->deleteSession(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMediaContent
     * @covers ::assertResponseIsSuccess
     */
    public function getMediaContentShouldReturnMediaObjectOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/media/%s/content\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::DOC_SCAN_MEDIA_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn(
                $this->createResponse(
                    200,
                    file_get_contents(TestData::DUMMY_SELFIE_FILE),
                    [
                        'Content-Type' => [
                            'image/png'
                        ]
                    ]
                )
            );

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            Media::class,
            $docScanService->getMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMediaContent
     * @covers ::assertResponseIsSuccess
     */
    public function getMediaContentShouldReturnNullWhenResponseWithNoContent()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn(
                $this->createResponse(
                    204,
                    '',
                    []
                )
            );

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $media = $docScanService->getMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID);
        $this->assertNull($media);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMediaContent
     * @covers ::assertResponseIsSuccess
     */
    public function getMediaContentShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/media/%s/content\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::DOC_SCAN_MEDIA_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn(
                $this->createResponse(
                    404
                )
            );

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->getMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteMediaContent
     * @covers ::assertResponseIsSuccess
     */
    public function deleteMediaContentShouldNotThrowExceptionOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/media/%s/content\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::DOC_SCAN_MEDIA_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('DELETE', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, file_get_contents(TestData::DOC_SCAN_SESSION_CREATION_RESPONSE)));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->deleteMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteMediaContent
     * @covers ::assertResponseIsSuccess
     */
    public function deleteMediaContentThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/media/%s/content\?sdkId=%s&nonce=.*?&timestamp=.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::DOC_SCAN_MEDIA_ID,
                            TestData::SDK_ID
                        );

                        $this->assertEquals('DELETE', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->deleteMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSupportedDocuments
     * @covers ::assertResponseIsSuccess
     */
    public function getSupportedDocumentsShouldReturnSupportedDocuments()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/supported-documents\?includeNonLatin=%s.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::INCLUDE_NON_LATIN
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, json_encode((object)[])));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            SupportedDocumentsResponse::class,
            $docScanService->getSupportedDocuments(TestData::INCLUDE_NON_LATIN)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSupportedDocuments
     * @covers ::assertResponseIsSuccess
     */
    public function getSupportedDocumentsShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/supported-documents\?includeNonLatin=%s.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::INCLUDE_NON_LATIN
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->getSupportedDocuments(TestData::INCLUDE_NON_LATIN);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createFaceCaptureResource
     * @covers ::assertResponseIsSuccess
     */
    public function createFaceCaptureResourceShouldReturnFaceCaptureResourceResponse()
    {
        $createFaceCaptureResourcePayloadMock = $this->createMock(CreateFaceCaptureResourcePayload::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/resources/face-capture.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(201, json_encode((object)[])));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            CreateFaceCaptureResourceResponse::class,
            $docScanService->createFaceCaptureResource(
                TestData::DOC_SCAN_SESSION_ID,
                $createFaceCaptureResourcePayloadMock
            )
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createFaceCaptureResource
     * @covers ::assertResponseIsSuccess
     */
    public function createFaceCaptureResourceShouldThrowExceptionOnFailedCall()
    {
        $createFaceCaptureResourcePayloadMock = $this->createMock(CreateFaceCaptureResourcePayload::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/resources/face-capture.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->createFaceCaptureResource(
            TestData::DOC_SCAN_SESSION_ID,
            $createFaceCaptureResourcePayloadMock
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::uploadFaceCaptureImage
     * @covers ::assertResponseIsSuccess
     */
    public function uploadFaceCaptureImageShouldNotThrowExceptionOnSuccessfulCall()
    {
        $uploadFaceCaptureImagePayloadMock = $this->createMock(UploadFaceCaptureImagePayload::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/resources/face-capture/%s/image.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SOME_RESOURCE_ID
                        );

                        $this->assertEquals('PUT', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->uploadFaceCaptureImage(
            TestData::DOC_SCAN_SESSION_ID,
            TestData::SOME_RESOURCE_ID,
            $uploadFaceCaptureImagePayloadMock
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::uploadFaceCaptureImage
     * @covers ::assertResponseIsSuccess
     */
    public function uploadFaceCaptureImageShouldThrowExceptionOnFailedCall()
    {
        $uploadFaceCaptureImagePayloadMock = $this->createMock(UploadFaceCaptureImagePayload::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/resources/face-capture/%s/image.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID,
                            TestData::SOME_RESOURCE_ID
                        );

                        $this->assertEquals('PUT', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->uploadFaceCaptureImage(
            TestData::DOC_SCAN_SESSION_ID,
            TestData::SOME_RESOURCE_ID,
            $uploadFaceCaptureImagePayloadMock
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::fetchSessionConfiguration
     * @covers ::assertResponseIsSuccess
     */
    public function getSessionConfigurationShouldReturnSessionConfiguration()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/configuration.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, json_encode((object)[])));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            SessionConfigurationResponse::class,
            $docScanService->fetchSessionConfiguration(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::fetchSessionConfiguration
     * @covers ::assertResponseIsSuccess
     */
    public function getSessionConfigurationShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/configuration.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->fetchSessionConfiguration(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::putIbvInstructions
     * @covers ::assertResponseIsSuccess
     */
    public function putIbvInstructionsShouldNotThrowExceptionOnSuccessfulCall()
    {
        $instructionsMock = $this->createMock(Instructions::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('PUT', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->putIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID,
            $instructionsMock
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::putIbvInstructions
     * @covers ::assertResponseIsSuccess
     */
    public function putIbvInstructionsShouldThrowExceptionOnFailedCall()
    {
        $instructionsMock = $this->createMock(Instructions::class);
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('PUT', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->putIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID,
            $instructionsMock
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIbvInstructions
     * @covers ::assertResponseIsSuccess
     */
    public function getIbvInstructionsShouldNotThrowExceptionOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->getIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIbvInstructions
     * @covers ::assertResponseIsSuccess
     */
    public function getIbvInstructionsShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->getIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIbvInstructionsPdf
     * @covers ::assertResponseIsSuccess
     */
    public function getIbvInstructionsPdfShouldReturnMediaObjectOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/pdf.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn(
                $this->createResponse(
                    200,
                    file_get_contents(TestData::DUMMY_PDF_FILE),
                    [
                        'Content-Type' => [
                            'application/pdf'
                        ]
                    ]
                )
            );

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            Media::class,
            $docScanService->getIbvInstructionsPdf(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIbvInstructionsPdf
     * @covers ::assertResponseIsSuccess
     */
    public function getIbvInstructionsPdfShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/pdf.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn(
                $this->createResponse(
                    404
                )
            );

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->getIbvInstructionsPdf(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::fetchInstructionsContactProfile
     * @covers ::assertResponseIsSuccess
     * @throws DocScanException|PemFileException
     */
    public function fetchInstructionsContactProfileShouldReturnContactProfileOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/contact-profile.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200, json_encode((object)[])));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->assertInstanceOf(
            ContactProfileResponse::class,
            $docScanService->fetchInstructionsContactProfile(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::fetchInstructionsContactProfile
     * @covers ::assertResponseIsSuccess
     */
    public function fetchInstructionsContactProfileShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/contact-profile.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('GET', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->fetchInstructionsContactProfile(TestData::DOC_SCAN_SESSION_ID);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::triggerIbvEmailNotification
     * @covers ::assertResponseIsSuccess
     */
    public function triggerIbvEmailNotificationShouldNotThrowExceptionOnSuccessfulCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/email.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(200));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $docScanService->triggerIbvEmailNotification(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::triggerIbvEmailNotification
     * @covers ::assertResponseIsSuccess
     */
    public function triggerIbvEmailNotificationShouldThrowExceptionOnFailedCall()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with(
                $this->callback(
                    function (RequestInterface $requestMessage) {
                        $expectedPathPattern = sprintf(
                            '~^%s/sessions/%s/instructions/email.*?~',
                            TestData::DOC_SCAN_BASE_URL,
                            TestData::DOC_SCAN_SESSION_ID
                        );

                        $this->assertEquals('POST', $requestMessage->getMethod());
                        $this->assertMatchesRegularExpression($expectedPathPattern, (string)$requestMessage->getUri());
                        return true;
                    }
                )
            )
            ->willReturn($this->createResponse(404));

        $docScanService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config(
                [
                    Config::HTTP_CLIENT => $httpClient,
                ]
            )
        );

        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage("Server responded with 404");

        $docScanService->triggerIbvEmailNotification(
            TestData::DOC_SCAN_SESSION_ID
        );
    }
}
