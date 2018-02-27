<?php

use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Payload;
use Yoti\Http\RestRequest;
use Yoti\Http\SignedRequest;
use Yoti\Http\AbstractRequest;

defined('PEM_FILE') || define('PEM_FILE', __DIR__ . '/../src/sample-data/yw-access-security.pem');
defined('SDK_ID') || define('SDK_ID', '990a3996-5762-4e8a-aa64-cb406fdb0e68');

class AbstractRequestTest extends PHPUnit\Framework\TestCase
{
    public $payload;
    public $signedRequest;
    public $amlResult;
    public $url;
    public $headers;
    public $request;
    public $postMethod = 'POST';

    public function setup()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        $this->payload = new Payload($amlProfile->getData());

        $this->signedRequest = new SignedRequest(
            $this->payload,
            '/aml-check',
            file_get_contents(PEM_FILE),
            SDK_ID,
            $this->postMethod
        );

        $this->url = $this->signedRequest->getApiRequestUrl(YotiClient::DEFAULT_CONNECT_API);
        $this->headers = $this->getHeaders();

        $this->request = $this->getMockForAbstractClass(
            '\Yoti\Http\AbstractRequest',
            // Constructor arguments
            [
                $this->headers,
                $this->url,
                $this->payload,
                $this->postMethod
            ],
            'request'
        );
    }

    public function testPostMethodCanSendPayload()
    {
        $this->assertTrue(AbstractRequest::canSendPayload($this->postMethod));
    }

    public function testGetMethodCannotSendPayload()
    {
        $this->assertFalse(AbstractRequest::canSendPayload('GET'));
    }

    public function testGetUrl()
    {
        $this->assertEquals($this->url, $this->request->getUrl());
    }

    public function testGetHttpMethod()
    {
        $this->assertEquals($this->postMethod, $this->request->getHttpMethod());
    }

    public function testGetPayload()
    {
        $this->assertEquals(
            $this->payload->getPayloadJSON(),
            $this->request->getPayload()->getPayloadJSON()
        );
    }

    public function testInvalidHttpMethod()
    {
        $this->expectException('Exception');
        $request = new RestRequest(
            $this->headers,
            $this->url,
            $this->payload,
            'InvalidMethod'
        );
    }

    public function testGetHttpHeaders()
    {
        $this->assertEquals(
            json_encode($this->headers),
            json_encode($this->request->getHttpHeaders())
        );
    }

    public function getHeaders()
    {
        $authKey = $this->getAuthKey();

        $signedMessage = $this->signedRequest->getSignedMessage();

        $headers = [
            YotiClient::AUTH_KEY_HEADER . ": {$authKey}",
            YotiClient::DIGEST_HEADER . ": {$signedMessage}",
            YotiClient::YOTI_SDK_HEADER . ': PHP',
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        return $headers;
    }

    /**
     * Extract Auth key content to be included in the header.
     *
     * @return string
     */
    public function getAuthKey()
    {
        $pemContent = file_get_contents(PEM_FILE);

        $_key = preg_split('/\r\n|\r|\n/', $pemContent);
        if(strpos($pemContent, 'BEGIN') !== FALSE)
        {
            array_shift($_key);
            array_pop($_key);
        }
        $pemContent = implode('', $_key);

        return $pemContent;
    }
}