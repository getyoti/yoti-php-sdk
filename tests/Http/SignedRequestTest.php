<?php

use Yoti\YotiClient;
use Yoti\Http\Payload;
use Yoti\Http\SignedRequest;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;

class SignedRequestTest extends PHPUnit\Framework\TestCase
{
    public $signedRequest;
    public $payload;
    public $messageToSign;

    public function setup()
    {
        $pem = $this->getDummyPrivateKey();
        $this->payload = $this->getDummyPayload();

        $this->signedRequest = new SignedRequest($this->payload, '/aml-check', $pem, SDK_ID, 'POST');

        $this->messagetoSign = 'POST&'.$this->signedRequest->getEndpointPath().'&'.$this->payload->getBase64Payload();
    }

    /**
     * Test getSignedMessage by verifying the signed message.
     */
    public function testGetSignedMessage()
    {
        $signedMessage = $this->signedRequest->getSignedMessage();

        $publicKey = openssl_pkey_get_public($this->getDummyPublicKey());

        $verify = openssl_verify($this->messagetoSign, base64_decode($signedMessage), $publicKey, OPENSSL_ALGO_SHA256);

        $this->assertEquals(1, $verify);
    }

    public function testGetApiRequestUrl()
    {
        $apiEndpoint = YotiClient::DEFAULT_CONNECT_API . '/aml-check';
        $this->assertContains(
            $apiEndpoint,
            $this->signedRequest->getApiRequestUrl(YotiClient::DEFAULT_CONNECT_API)
        );
    }

    public function getDummyPayload()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        return new Payload($amlProfile->getData());
    }

    public function getDummyPrivateKey()
    {
        return file_get_contents(AML_PRIVATE_KEY);
    }

    public function getDummyPublicKey()
    {
        return file_get_contents(AML_PUBLIC_KEY);
    }
}