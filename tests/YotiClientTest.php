<?php

namespace YotiTest;

use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\AmlResult;
use Yoti\Http\RequestHandlerInterface;
use Yoti\Http\Response;

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
     * Test the use of pem file path
     *
     * @covers ::__construct
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
     */
    public function testGetActivityDetails()
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(file_get_contents(RECEIPT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->method('execute')->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        $ad = $yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN);

        $this->assertInstanceOf(\Yoti\ActivityDetails::class, $ad);
    }

    /**
     * @covers ::performAmlCheck
     */
    public function testPerformAmlCheck()
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn(file_get_contents(AML_CHECK_RESULT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->method('execute')->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, $this->pem);
        $yotiClient->setRequestHandler($requestHandler);

        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $result = $yotiClient->performAmlCheck($amlProfile);

        $this->assertInstanceOf(AmlResult::class, $result);
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
}
