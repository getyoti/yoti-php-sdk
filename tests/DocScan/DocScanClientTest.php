<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\DocScan\DocScanClient;
use Yoti\DocScan\Session\Create\CreateSessionResult;
use Yoti\DocScan\Session\Create\FaceCapture\CreateFaceCaptureResourcePayload;
use Yoti\DocScan\Session\Create\FaceCapture\UploadFaceCaptureImagePayload;
use Yoti\DocScan\Session\Create\SessionSpecification;
use Yoti\DocScan\Session\Instructions\Instructions;
use Yoti\DocScan\Session\Retrieve\Configuration\SessionConfigurationResponse;
use Yoti\DocScan\Session\Retrieve\CreateFaceCaptureResourceResponse;
use Yoti\DocScan\Session\Retrieve\GetSessionResult;
use Yoti\DocScan\Support\SupportedDocumentsResponse;
use Yoti\Media\Media;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;
use Yoti\Util\Json;

/**
 * @coversDefaultClass \Yoti\DocScan\DocScanClient
 */
class DocScanClientTest extends TestCase
{
    private const SOME_ENV_URL = 'https://example.com/env/api';
    private const SOME_OPTION_URL = 'https://example.com/option/api';

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
     */
    public function testDefaultApiUrl()
    {
        $this->assertApiUrlStartsWith(TestData::DOC_SCAN_BASE_URL);
    }

    /**
     * @test
     * @covers ::__construct
     * @backupGlobals enabled
     */
    public function testApiUrlOptionOverridesEnvironmentVariable()
    {
        $_SERVER['YOTI_DOC_SCAN_API_URL'] = self::SOME_ENV_URL;
        $this->assertApiUrlStartsWith(self::SOME_OPTION_URL, self::SOME_OPTION_URL);
    }

    /**
     * @test
     * @covers ::__construct
     * @backupGlobals enabled
     */
    public function testApiUrlEnvironmentVariable()
    {
        $_SERVER['YOTI_DOC_SCAN_API_URL'] = self::SOME_ENV_URL;
        $this->assertApiUrlStartsWith(self::SOME_ENV_URL);
    }

    /**
     * @test
     * @covers ::__construct
     * @backupGlobals enabled
     */
    public function testEmptyApiUrlEnvironmentVariable()
    {
        $_SERVER['YOTI_DOC_SCAN_API_URL'] = '';
        $this->assertApiUrlStartsWith(TestData::DOC_SCAN_BASE_URL);
    }

    /**
     * Asserts API URL starts with expected URL.
     *
     * @param string $expectedUrl
     * @param string $clientApiUrl
     */
    private function assertApiUrlStartsWith($expectedUrl, $clientApiUrl = null)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(file_get_contents(TestData::DOC_SCAN_SESSION_CREATION_RESPONSE));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with($this->callback(function ($requestMessage) use ($expectedUrl) {
                $this->assertStringStartsWith(
                    $expectedUrl,
                    (string) $requestMessage->getUri()
                );
                return true;
            }))
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
            Config::API_URL => $clientApiUrl,
        ]);

        $sessionSpecificationMock = $this->createMock(SessionSpecification::class);
        $sessionSpecificationMock->method('jsonSerialize')->willReturn(new \stdClass());

        $docScanClient->createSession($sessionSpecificationMock);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::createSession
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
        $sessionSpecificationMock->method('jsonSerialize')->willReturn((object)['someKey' => 'someValue']);

        $this->assertInstanceOf(
            CreateSessionResult::class,
            $docScanClient->createSession($sessionSpecificationMock)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSession
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
     * @covers ::getMediaContent
     */
    public function testGetMediaIfNoContent()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(204);
        $response->method('getHeader')->willReturn([ 'image/png' ]);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertNull(
            $docScanClient->getMediaContent(TestData::DOC_SCAN_SESSION_ID, TestData::DOC_SCAN_MEDIA_ID)
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::deleteMediaContent
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

    /**
     * @test
     * @covers ::getSupportedDocuments
     */
    public function testGetSupportedDocuments()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            SupportedDocumentsResponse::class,
            $docScanClient->getSupportedDocuments()
        );
    }

    /**
     * @test
     * @covers ::createFaceCaptureResource
     */
    public function testCreateFaceCaptureResource()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(201);

        $createFaceCaptureResourcePayloadMock = $this->createMock(CreateFaceCaptureResourcePayload::class);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            CreateFaceCaptureResourceResponse::class,
            $docScanClient->createFaceCaptureResource(
                TestData::DOC_SCAN_SESSION_ID,
                $createFaceCaptureResourcePayloadMock
            )
        );
    }

    /**
     * @test
     * @covers ::uploadFaceCaptureImage
     */
    public function testUploadFaceCaptureImage()
    {
        $response = $this->createMock(ResponseInterface::class);
        $uploadFaceCaptureImagePayloadMock = $this->createMock(UploadFaceCaptureImagePayload::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->uploadFaceCaptureImage(
            TestData::DOC_SCAN_SESSION_ID,
            TestData::SOME_RESOURCE_ID,
            $uploadFaceCaptureImagePayloadMock
        );
    }

    /**
     * @test
     * @covers ::getSessionConfiguration
     */
    public function testGetSessionConfiguration()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            SessionConfigurationResponse::class,
            $docScanClient->getSessionConfiguration(TestData::DOC_SCAN_SESSION_ID)
        );
    }

    /**
     * @test
     * @covers ::putIbvInstructions
     */
    public function testPutIbvInstructions()
    {
        $response = $this->createMock(ResponseInterface::class);
        $instructionsMock = $this->createMock(Instructions::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->putIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID,
            $instructionsMock
        );
    }

    /**
     * @test
     * @covers ::getIbvInstructions
     */
    public function testGetIbvInstructions()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->getIbvInstructions(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::getIbvInstructionsPdf
     */
    public function testGetIbvInstructionsPdf()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->getIbvInstructionsPdf(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::fetchInstructionsContactProfile
     */
    public function testFetchInstructionsContactProfile()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->fetchInstructionsContactProfile(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     * @covers ::triggerIbvEmailNotification
     */
    public function testTriggerIbvEmailNotification()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode((object)[]));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $docScanClient = new DocScanClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $docScanClient->triggerIbvEmailNotification(
            TestData::DOC_SCAN_SESSION_ID
        );
    }

    /**
     * @test
     *
     * Parse session response with identity profile
     */
    public function testParseIdentityProfileResponse()
    {
        $sessionDataString = Json::decode(file_get_contents(TestData::SESSION_RESULT_IDENTITY_PROFILE));
        $sessionResult = new GetSessionResult($sessionDataString);

        $this->assertEquals('DONE', $sessionResult->getIdentityProfile()->getResult());
        $this->assertEquals('someStringHere', $sessionResult->getIdentityProfile()->getSubjectId());
        $this->assertEquals(
            'MANDATORY_DOCUMENT_COULD_NOT_BE_PROVIDED',
            $sessionResult->getIdentityProfile()->getFailureReason()->getStringCode()
        );

        $this->assertEquals(
            'UK_TFIDA',
            $sessionResult->getIdentityProfile()->getIdentityProfileReport()->trust_framework
        );
        $this->assertEquals(
            'DBS',
            $sessionResult->getIdentityProfile()->getIdentityProfileReport()->schemes_compliance[0]['scheme']['type']
        );
        $this->assertEquals(
            'STANDARD',
            $sessionResult->getIdentityProfile()->getIdentityProfileReport()
                ->schemes_compliance[0]['scheme']['objective']
        );
        $this->assertEquals(
            'some string here',
            $sessionResult->getIdentityProfile()->getIdentityProfileReport()
                ->schemes_compliance[0]['requirements_not_met_info']
        );
        $this->assertTrue(
            $sessionResult->getIdentityProfile()->getIdentityProfileReport()
                ->schemes_compliance[0]['requirements_met']
        );
    }
}
