<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\Request;
use Yoti\Http\Payload;
use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\RequestSigner;

class RequestSignerTest extends TestCase
{
    /**
     * @var Payload
     */
    public $payload;
    /**
     * @var Request
     */
    public $request;
    public $messageToSign;

    public function setup()
    {
        $pem = $this->getDummyPrivateKey();
        $this->payload = $this->getDummyPayload();

        $this->request = new Request(
            YotiClient::DEFAULT_CONNECT_API,
            $pem ,
            SDK_ID,
            'PHP'
        );
    }

    /**
     * Test verifying a signed message.
     */
    public function testShouldVerifySignedMessage()
    {
        $signedData = RequestSigner::signRequest(
            $this->request,
            $this->payload,
            '/aml-check',
            'POST'
        );
        $signedMessage = $signedData[RequestSigner::SIGNED_MESSAGE_KEY];
        $endpointPath = $signedData[RequestSigner::END_POINT_PATH_KEY];
        $messageToSign = 'POST&'.$endpointPath.'&'.$this->payload->getBase64Payload();

        $publicKey = openssl_pkey_get_public($this->getDummyPublicKey());

        $verify = openssl_verify($messageToSign, base64_decode($signedMessage), $publicKey, OPENSSL_ALGO_SHA256);

        $this->assertEquals(1, $verify);
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