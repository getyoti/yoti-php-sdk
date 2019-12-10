<?php

namespace YotiTest;

use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\AmlResult;
use Yoti\Http\RequestHandlerInterface;
use Yoti\Http\Response;
use Yoti\ShareUrl\DynamicScenario;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;

/**
 * @coversDefaultClass \Yoti\YotiClient
 */
class YotiClientTest extends TestCase
{
    /**
     * @var YotiClient
     */
    public $yotiClient;

    /**
     * @var string Pem file contents
     */
    public $pem;

    /**
     * @var \Yoti\Entity\AmlProfile
     */
    public $amlProfile;

    /**
     * @var array Aml Result
     */
    public $amlResult = [];

    public function setUp()
    {
        $this->pem = file_get_contents(PEM_FILE);
    }

    /**
     * Test empty SDK ID
     *
     * @covers ::__construct
     * @covers ::setSdkId
     *
     * @expectedException \Yoti\Exception\YotiClientException
     * @expectedExceptionMessage SDK ID is required
     */
    public function testEmptySdkId()
    {
        new YotiClient('', PEM_FILE);
    }

    /**
     * Test the use of pem file path
     *
     * @covers ::__construct
     * @covers ::extractPemContent
     * @covers ::checkRequiredModules
     */
    public function testCanUsePemFile()
    {
        $yotiClientObj = new YotiClient(SDK_ID, PEM_FILE);
        $this->assertInstanceOf(\Yoti\YotiClient::class, $yotiClientObj);
    }

    /**
     * Test the use of pem file path with file:// stream wrapper
     *
     * @covers ::__construct
     * @covers ::extractPemContent
     */
    public function testCanUsePemFileStreamWrapper()
    {
        $yotiClientObj = new YotiClient(SDK_ID, 'file://' . PEM_FILE);
        $this->assertInstanceOf(\Yoti\YotiClient::class, $yotiClientObj);
    }

    /**
     * Test passing invalid pem file path with file:// stream wrapper
     *
     * @covers ::__construct
     * @covers ::extractPemContent
     *
     * @expectedException \Yoti\Exception\YotiClientException
     * @expectedExceptionMessage PEM file was not found
     */
    public function testInvalidPemFileStreamWrapperPath()
    {
        new YotiClient(SDK_ID, 'file://invalid_file_path.pem');
    }

    /**
     * Test passing pem file with invalid contents
     *
     * @covers ::__construct
     * @covers ::extractPemContent
     *
     * @expectedException \Yoti\Exception\YotiClientException
     * @expectedExceptionMessage PEM file path or content is invalid
     */
    public function testInvalidPemFileContents()
    {
        new YotiClient(SDK_ID, INVALID_PEM_FILE);
    }

    /**
     * Test passing invalid pem string
     *
     * @covers ::__construct
     * @covers ::extractPemContent
     *
     * @expectedException \Yoti\Exception\YotiClientException
     * @expectedExceptionMessage PEM file path or content is invalid
     */
    public function testInvalidPemString()
    {
        new YotiClient(SDK_ID, 'invalid_pem_string');
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::decryptConnectToken
     * @covers ::setRequestHandler
     * @covers ::sendRequest
     * @covers ::sendConnectRequest
     * @covers ::getReceipt
     * @covers ::processJsonResponse
     * @covers ::checkForReceipt
     * @covers ::setSdkId
     */
    public function testGetActivityDetails()
    {
        $expectedPathPattern = sprintf(
            '~^%s/profile/%s\?appId=%s&nonce=.*?&timestamp=.*?~',
            CONNECT_BASE_URL,
            YOTI_CONNECT_TOKEN_DECRYPTED,
            SDK_ID
        );

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(file_get_contents(RECEIPT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->expects($this->exactly(1))
            ->method('execute')
            ->with($this->callback(function ($request) use ($expectedPathPattern) {
                $this->assertEquals('GET', $request->getMethod());
                $this->assertRegExp($expectedPathPattern, $request->getUrl());
                $this->assertEquals(PEM_AUTH_KEY, $request->getHeaders()['X-Yoti-Auth-Key']);
                return true;
            }))
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        $ad = $yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);

        $this->assertInstanceOf(\Yoti\ActivityDetails::class, $ad);
    }


    /**
     * @covers ::setSdkIdentifier
     * @covers ::setSdkVersion
     * @covers ::sendConnectRequest
     */
    public function testSetSdkHeaders()
    {
        $expectedSdkIdentifier = 'Drupal';
        $expectedSdkVersion = '1.2.3';

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(file_get_contents(RECEIPT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->expects($this->exactly(1))
            ->method('execute')
            ->with($this->callback(function ($request) use ($expectedSdkIdentifier, $expectedSdkVersion) {
                $headers = $request->getHeaders();
                $this->assertEquals($expectedSdkIdentifier, $headers['X-Yoti-SDK']);
                $this->assertEquals("{$expectedSdkIdentifier}-{$expectedSdkVersion}", $headers['X-Yoti-SDK-Version']);
                return true;
            }))
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);
        $yotiClient->setSdkIdentifier($expectedSdkIdentifier);
        $yotiClient->setSdkVersion($expectedSdkVersion);

        $yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::isResponseSuccess
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\ActivityDetailsException
     */
    public function testGetActivityDetailsFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $yotiClient = $this->createClientWithErrorResponse($statusCode);
        $yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);
    }

    /**
     * @covers ::performAmlCheck
     * @covers ::setRequestHandler
     * @covers ::sendRequest
     * @covers ::sendConnectRequest
     * @covers ::processJsonResponse
     * @covers ::validateAmlResult
     * @covers \Yoti\Entity\AmlAddress::__construct
     * @covers \Yoti\Entity\AmlProfile::__construct
     * @covers \Yoti\Entity\Country::__construct
     */
    public function testPerformAmlCheck()
    {
        $expectedPathPattern = sprintf(
            '~^%s/aml-check\?appId=%s&nonce=.*?&timestamp=.*?~',
            CONNECT_BASE_URL,
            SDK_ID
        );

        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(file_get_contents(AML_CHECK_RESULT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->expects($this->exactly(1))
            ->method('execute')
            ->with(
                $this->callback(function ($request) use ($amlProfile, $expectedPathPattern) {
                    $this->assertEquals('POST', $request->getMethod());
                    $this->assertEquals((string) $amlProfile, (string) $request->getPayload());
                    $this->assertRegExp($expectedPathPattern, $request->getUrl());
                    $this->assertEquals('application/json', $request->getHeaders()['Content-Type']);
                    return true;
                })
            )
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        $result = $yotiClient->performAmlCheck($amlProfile);

        $this->assertInstanceOf(AmlResult::class, $result);
    }

    /**
     * @covers ::performAmlCheck
     * @covers ::validateAmlResult
     * @covers ::getErrorMessage
     * @covers ::isResponseSuccess
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\AmlException
     */
    public function testPerformAmlCheckFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $yotiClient = $this->createClientWithErrorResponse($statusCode);
        $yotiClient->performAmlCheck($this->createMock(AmlProfile::class));
    }

    /**
     * Test invalid Token
     *
     * @covers ::getActivityDetails
     */
    public function testInvalidConnectToken()
    {
        $yotiClient = new YotiClient(SDK_ID, $this->pem);

        $this->expectException('Exception');
        $yotiClient->getActivityDetails(INVALID_YOTI_CONNECT_TOKEN);
    }

    /**
     * Test invalid http header value for X-Yoti-SDK
     *
     * @covers ::__construct
     * @covers ::sendRequest
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testInvalidSdkIdentifierConstructor()
    {
        $yotiClient = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            'Invalid'
        );
        $amlProfile = $this->createMock(AmlProfile::class);
        $yotiClient->performAmlCheck($amlProfile);
    }

    /**
     * Test invalid http header value for X-Yoti-SDK
     *
     * @covers ::setSdkIdentifier
     * @covers ::sendRequest
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage 'Invalid' is not in the list of accepted identifiers: PHP, WordPress, Drupal, Joomla
     */
    public function testInvalidSdkIdentifier()
    {
        $yotiClient = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API
        );
        $yotiClient->setSdkIdentifier('Invalid');

        $amlProfile = $this->createMock(AmlProfile::class);
        $yotiClient->performAmlCheck($amlProfile);
    }

    /**
     * Test invalid http header value for X-Yoti-SDK-Version
     *
     * @covers ::setSdkVersion
     *
     * @expectedException \Yoti\Exception\RequestException
     * @expectedExceptionMessage Yoti SDK version must be a string
     */
    public function testInvalidSdkVersion()
    {
        $yotiClient = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API
        );
        $yotiClient->setSdkVersion(['WrongVersion']);

        $amlProfile = $this->createMock(AmlProfile::class);
        $yotiClient->performAmlCheck($amlProfile);
    }

    /**
     * Test X-Yoti-SDK http header value for each allowed identifer.
     *
     * @covers ::__construct
     * @covers ::setSdkIdentifier
     *
     * @dataProvider allowedIdentifierDataProvider
     */
    public function testCanUseAllowedSdkIdentifier($identifier)
    {
        $yotiClient = new YotiClient(
            SDK_ID,
            $this->pem,
            YotiClient::DEFAULT_CONNECT_API,
            $identifier
        );
        $yotiClient->setSdkIdentifier($identifier);
        $this->assertInstanceOf(YotiClient::class, $yotiClient);
    }

    /**
     * Data provider to check allowed SDK identifiers.
     *
     * @return array
     */
    public function allowedIdentifierDataProvider()
    {
        return [
            ['PHP'],
            ['WordPress'],
            ['Joomla'],
            ['Drupal'],
        ];
    }

    /**
     * @covers ::createShareUrl
     * @covers ::sendConnectRequest
     * @covers ::processJsonResponse
     */
    public function testCreateShareUrl()
    {
        $expectedUrl = YotiClient::DEFAULT_CONNECT_API . sprintf('/qrcodes/apps/%s', SDK_ID) . '?appId=' . SDK_ID;
        $expectedUrlPattern = sprintf('~%s.*?nonce=.*?&timestamp=.*?~', preg_quote($expectedUrl));
        $expectedQrCode = 'https://dynamic-code.yoti.com/CAEaJDRjNTQ3M2IxLTNiNzktNDg3My1iMmM4LThiMTQxZDYwMjM5ODAC';
        $expectedRefId = '4c5473b1-3b79-4873-b2c8-8b141d602398';

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint('/test-callback-url')
            ->withPolicy(
                (new DynamicPolicyBuilder())->build()
            )
            ->build();

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(json_encode([
            'qrcode' => $expectedQrCode,
            'ref_id' => $expectedRefId,
        ]));
        $response->method('getStatusCode')->willReturn(201);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $requestHandler
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($request) use ($expectedUrlPattern, $dynamicScenario) {
                $this->assertRegExp($expectedUrlPattern, $request->getUrl());
                $this->assertEquals(json_encode($dynamicScenario), $request->getPayload());
                return true;
            }))
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        $shareUrlResult = $yotiClient->createShareUrl($dynamicScenario);

        $this->assertEquals($expectedQrCode, $shareUrlResult->getShareUrl());
        $this->assertEquals($expectedRefId, $shareUrlResult->getRefId());
    }

    /**
     * @covers ::createShareUrl
     * @covers ::isResponseSuccess
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\ShareUrlException
     */
    public function testCreateShareUrlFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $yotiClient = $this->createClientWithErrorResponse($statusCode);
        $yotiClient->createShareUrl($this->createMock(DynamicScenario::class));
    }

    /**
     * Provides HTTP error status codes.
     */
    public function httpErrorStatusCodeProvider()
    {
        $clientCodes = [400, 401, 402, 403, 404];
        $serverCodes = [500, 501, 502, 503, 504];

        return array_map(
            function ($code) {
                return [$code];
            },
            $clientCodes + $serverCodes
        );
    }

    /**
     * @param int $statusCode
     *
     * @return \Yoti\YotiClient
     */
    private function createClientWithErrorResponse($statusCode)
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn('{}');
        $response->method('getStatusCode')->willReturn($statusCode);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler
            ->method('execute')
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        return $yotiClient;
    }
}
