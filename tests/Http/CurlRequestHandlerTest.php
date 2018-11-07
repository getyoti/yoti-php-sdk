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

class CurlRequestHandlerTest extends TestCase
{
    public $pem;
    /**
     * @var CurlRequestHandler
     */
    public $requestHandler;
    /**
     * @var Payload
     */
    public $payload;

    public $requestSigner;

    public $postMethod = CurlRequestHandler::METHOD_POST;

    public function setup()
    {
        $endpoint = '/aml-check';
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile->getData());
        $this->postMethod = CurlRequestHandler::METHOD_POST;

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

    public function testGetPem()
    {
        $this->assertEquals($this->pem, $this->requestHandler->getPem());
    }

    public function testGetSdkId()
    {
        $this->assertEquals(SDK_ID, $this->requestHandler->getSdkId());
    }

    public function testInvalidHttpMethod()
    {
        $this->expectException('\Yoti\Exception\RequestException');
        $this->requestHandler->sendRequest(
            '/aml-check',
            'InvalidHttpMethod',
            $this->payload
        );
    }

    public function testClassIsInstanceOfAbstractRequestHandler()
    {
        $this->assertInstanceOf(AbstractRequestHandler::class, $this->requestHandler);
    }

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