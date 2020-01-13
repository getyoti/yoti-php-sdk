<?php

namespace YotiTest\Profile;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Profile\ActivityDetails;
use Yoti\Profile\Service;
use Yoti\Util\Config;
use Yoti\Util\PemFile;
use YotiTest\TestCase;
use YotiTest\TestData;

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
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->with($this->callback(function ($requestMessage) {
                $expectedPathPattern = sprintf(
                    '~^%s/profile/%s\?appId=%s&nonce=.*?&timestamp=.*?~',
                    TestData::CONNECT_BASE_URL,
                    TestData::YOTI_CONNECT_TOKEN_DECRYPTED,
                    TestData::SDK_ID
                );

                $expectedAuthKey = file_get_contents(TestData::PEM_AUTH_KEY);

                $this->assertEquals('GET', $requestMessage->getMethod());
                $this->assertRegExp($expectedPathPattern, (string) $requestMessage->getUri());
                $this->assertEquals($expectedAuthKey, $requestMessage->getHeader('X-Yoti-Auth-Key')[0]);
                return true;
            }))
            ->willReturn($this->createResponse(200, file_get_contents(TestData::RECEIPT_JSON)));

        $profileService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );

        $this->assertInstanceOf(
            ActivityDetails::class,
            $profileService->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN))
        );
    }

    /**
     * @covers ::__construct
     */
    public function testSetSdkHeaders()
    {
        $expectedSdkIdentifier = 'Drupal';
        $expectedSdkVersion = '1.2.3';

        $response = $this->createResponse(200, file_get_contents(TestData::RECEIPT_JSON));

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

        $profileService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
                Config::SDK_IDENTIFIER => $expectedSdkIdentifier,
                Config::SDK_VERSION => $expectedSdkVersion,
            ])
        );

        $profileService->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN));
    }

    /**
     * @covers ::getActivityDetails
     *
     * @dataProvider httpErrorStatusCodeProvider
     */
    public function testGetActivityDetailsFailure($statusCode)
    {
        $this->expectException(\Yoti\Exception\ActivityDetailsException::class);

        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $profileService = $this->createProfileServiceWithResponse($statusCode);
        $profileService->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN));
    }

    /**
     * Test invalid Token
     *
     * @covers ::getActivityDetails
     */
    public function testInvalidConnectToken()
    {
        $this->expectException(\Yoti\Exception\ActivityDetailsException::class, 'Could not decrypt connect token');

        $profileService = new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config()
        );

        $profileService->getActivityDetails(TestData::INVALID_YOTI_CONNECT_TOKEN);
    }

    /**
     * @covers ::getActivityDetails
     */
    public function testSharingOutcomeFailure()
    {
        $this->expectException(\Yoti\Exception\ActivityDetailsException::class, 'Outcome was unsuccessful');

        $json = json_decode(file_get_contents(TestData::RECEIPT_JSON), true);
        $json['receipt']['sharing_outcome'] = 'FAILURE';

        $profileService = $this->createProfileServiceWithResponse(200, json_encode($json));
        $profileService->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN));
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::checkForReceipt
     */
    public function testMissingReceipt()
    {
        $this->expectException(\Yoti\Exception\ReceiptException::class, 'Receipt not found in response');

        $profileService = $this->createProfileServiceWithResponse(200);
        $profileService->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN));
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

        return new Service(
            TestData::SDK_ID,
            PemFile::fromFilePath(TestData::PEM_FILE),
            new Config([
                Config::HTTP_CLIENT => $httpClient,
            ])
        );
    }
}
