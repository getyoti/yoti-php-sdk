<?php

namespace YotiTest\Http;

use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\Payload;
use Yoti\Http\RestRequest;
use Yoti\Http\SignedRequest;
use YotiTest\TestCase;

class RestRequestTest extends TestCase
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
        $pem = file_get_contents(PEM_FILE);

        $this->signedRequest = new SignedRequest(
            $this->payload,
            '/aml-check',
            $pem,
            SDK_ID,
            $this->postMethod
        );

        $this->url = $this->signedRequest->getApiRequestUrl(YotiClient::DEFAULT_CONNECT_API);
        $this->headers = $this->getHeaders();

        $this->request = new RestRequest(
            $this->headers,
            $this->url,
            $this->payload,
            $this->postMethod
        );
    }

    public function testMethodExecExists()
    {
        $this->assertTrue(method_exists($this->request, 'exec'));
    }

    public function testMethodCanSendPayloadExists()
    {
        $this->assertTrue(method_exists($this->request, 'canSendPayload'));
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