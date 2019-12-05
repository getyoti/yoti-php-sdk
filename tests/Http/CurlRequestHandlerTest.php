<?php

namespace YotiTest\Http;

use Yoti\YotiClient;
use YotiTest\TestCase;
use Yoti\Http\Payload;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\RequestSigner;
use Yoti\Http\CurlRequestHandler;
use Yoti\Http\AbstractRequestHandler;

/**
 * @coversDefaultClass \Yoti\Http\CurlRequestHandler
 */
class CurlRequestHandlerTest extends TestCase
{
    /**
     * @var string Pem file contents
     */
    public $pem;

    /**
     * @var CurlRequestHandler
     */
    public $requestHandler;

    /**
     * @var \Yoti\Http\Payload
     */
    public $payload;

    /**
     * @var Yoti\Http\RequestSigner
     */
    public $requestSigner;

    public function setup()
    {
        $endpoint = '/aml-check';
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile);
        $this->pem = file_get_contents(PEM_FILE);

        $this->requestSigner = new RequestSigner(
            $this->requestHandler,
            $endpoint,
            'POST'
        );

        $this->requestHandler = new CurlRequestHandler(
            YotiClient::DEFAULT_CONNECT_API,
            $this->pem,
            SDK_ID,
            'PHP'
        );
    }

    /**
     * @covers ::getPem
     */
    public function testGetPem()
    {
        $this->assertEquals($this->pem, $this->requestHandler->getPem());
    }

    /**
     * @covers ::getSdkId
     */
    public function testGetSdkId()
    {
        $this->assertEquals(SDK_ID, $this->requestHandler->getSdkId());
    }

    /**
     * @covers ::sendRequest
     */
    public function testInvalidHttpMethod()
    {
        $this->expectException('\Yoti\Exception\RequestException');
        $this->requestHandler->sendRequest(
            '/aml-check',
            'InvalidHttpMethod',
            $this->payload
        );
    }

    /**
     * @covers ::sendRequest
     */
    public function testClassIsInstanceOfAbstractRequestHandler()
    {
        $this->assertInstanceOf(AbstractRequestHandler::class, $this->requestHandler);
    }

    /**
     * @covers ::sendRequest
     */
    public function testSendRequest()
    {
        $expectedResult['response'] = file_get_contents(RECEIPT_JSON);
        $expectedResult['http_code'] = 200;

        $curlRequestHandler = $this->getMockBuilder('\Yoti\Http\CurlRequestHandler')
            ->setConstructorArgs([YotiClient::DEFAULT_CONNECT_API, $this->pem, SDK_ID, 'PHP'])
            ->setMethods(['executeRequest'])
            ->getMock();

        // Configure the stub.
        $curlRequestHandler->method('executeRequest')
            ->willReturn($expectedResult);

        $result = $curlRequestHandler->sendRequest(
            '/profile/fakeToken',
            'GET'
        );
        $this->assertEquals(json_encode($expectedResult), json_encode($result));
    }
}
