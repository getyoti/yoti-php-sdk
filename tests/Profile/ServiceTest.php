<?php

namespace YotiTest\Profile;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Profile\ActivityDetails;
use Yoti\Profile\Service;
use Yoti\Util\Config;
use Yoti\Util\PemFile;
use YotiTest\TestCase;

use function GuzzleHttp\Psr7\stream_for;

/**
 * @coversDefaultClass \Yoti\Profile\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @covers ::getActivityDetails
     * @covers ::decryptConnectToken
     * @covers ::checkForReceipt
     */
    public function testGetActivityDetails()
    {
        $expectedPathPattern = sprintf(
            '~^%s/profile/%s\?appId=%s&nonce=.*?&timestamp=.*?~',
            CONNECT_BASE_URL,
            YOTI_CONNECT_TOKEN_DECRYPTED,
            SDK_ID
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with($this->callback(function ($requestMessage) use ($expectedPathPattern) {
                $this->assertEquals('GET', $requestMessage->getMethod());
                $this->assertRegExp($expectedPathPattern, (string) $requestMessage->getUri());
                $this->assertEquals(PEM_AUTH_KEY, $requestMessage->getHeader('X-Yoti-Auth-Key')[0]);
                return true;
            }))
            ->willReturn($this->createResponse(200, file_get_contents(RECEIPT_JSON)));

        $profileService = new Service(new Config([
            Config::HTTP_CLIENT => $httpClient,
        ]));

        $this->assertInstanceOf(
            ActivityDetails::class,
            $profileService->getActivityDetails(YOTI_CONNECT_TOKEN, PemFile::fromFilePath(PEM_FILE), SDK_ID)
        );
    }

    /**
     * @covers ::__construct
     */
    public function testSetSdkHeaders()
    {
        $expectedSdkIdentifier = 'Drupal';
        $expectedSdkVersion = '1.2.3';

        $response = $this->createResponse(200, file_get_contents(RECEIPT_JSON));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with($this->callback(function ($requestMessage) use ($expectedSdkIdentifier, $expectedSdkVersion) {
                $this->assertEquals(
                    $expectedSdkIdentifier,
                    $requestMessage->getHeader('X-Yoti-SDK')[0]
                );
                $this->assertEquals(
                    "{$expectedSdkIdentifier}-{$expectedSdkVersion}",
                    $requestMessage->getHeader('X-Yoti-SDK-Version')[0]
                );
                return true;
            }))
            ->willReturn($response);

        $profileService = new Service(new Config([
            Config::HTTP_CLIENT => $httpClient,
            Config::SDK_IDENTIFIER => $expectedSdkIdentifier,
            Config::SDK_VERSION => $expectedSdkVersion,
        ]));

        $profileService->getActivityDetails(
            YOTI_CONNECT_TOKEN,
            Pemfile::fromFilePath(PEM_FILE),
            SDK_ID
        );
    }

    /**
     * @covers ::getActivityDetails
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\ActivityDetailsException
     */
    public function testGetActivityDetailsFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $profileService = $this->createProfileServiceWithResponse($statusCode);
        $profileService->getActivityDetails(YOTI_CONNECT_TOKEN, PemFile::fromFilePath(PEM_FILE), SDK_ID);
    }

    /**
     * Test invalid Token
     *
     * @covers ::getActivityDetails
     *
     * @expectedException \Yoti\Exception\ActivityDetailsException
     * @expectedExceptionMessage Could not decrypt connect token
     */
    public function testInvalidConnectToken()
    {
        $profileService = new Service(new Config());

        $profileService->getActivityDetails(
            INVALID_YOTI_CONNECT_TOKEN,
            Pemfile::fromFilePath(PEM_FILE),
            SDK_ID
        );
    }

    /**
     * @covers ::getActivityDetails
     *
     * @expectedException \Yoti\Exception\ActivityDetailsException
     * @expectedExceptionMessage Outcome was unsuccessful
     */
    public function testSharingOutcomeFailure()
    {
        $json = json_decode(file_get_contents(RECEIPT_JSON), true);
        $json['receipt']['sharing_outcome'] = 'FAILURE';

        $profileService = $this->createProfileServiceWithResponse(200, json_encode($json));
        $profileService->getActivityDetails(YOTI_CONNECT_TOKEN, PemFile::fromFilePath(PEM_FILE), SDK_ID);
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::checkForReceipt
     *
     * @expectedException \Yoti\Exception\ReceiptException
     * @expectedExceptionMessage Receipt not found in response
     */
    public function testMissingReceipt()
    {
        $profileService = $this->createProfileServiceWithResponse(200);
        $profileService->getActivityDetails(YOTI_CONNECT_TOKEN, PemFile::fromFilePath(PEM_FILE), SDK_ID);
    }

    /**
     * @param int $statusCode
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createResponse($statusCode, $body = '{}')
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for($body));
        $response->method('getStatusCode')->willReturn($statusCode);
        return $response;
    }

    /**
     * @param int $statusCode
     *
     * @return \Yoti\Profile\Service
     */
    private function createProfileServiceWithResponse($statusCode, $body = '{}')
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->method('sendRequest')
            ->willReturn($this->createResponse($statusCode, $body));

        $profileService = new Service(new Config([
            Config::HTTP_CLIENT => $httpClient,
        ]));

        return $profileService;
    }
}
