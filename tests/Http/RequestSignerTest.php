<?php

namespace YotiTest\Http;

use YotiTest\TestCase;
use Yoti\Http\Payload;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Http\RequestSigner;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Http\RequestSigner
 */
class RequestSignerTest extends TestCase
{
    /**
     * @var \Yoti\Http\Payload
     */
    private $payload;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var string
     */
    private $publicKey;

    public function setup()
    {
        $this->pem = file_get_contents(AML_PRIVATE_KEY);
        $this->publicKey = file_get_contents(AML_PUBLIC_KEY);
        $this->payload = $this->getDummyPayload();
    }

    /**
     * @covers ::sign
     * @covers ::generateEndPointPath
     * @covers ::validateSignedMessage
     * @covers ::generateNonce
     */
    public function testSign()
    {
        $signedData = RequestSigner::sign(
            PemFile::fromString($this->pem),
            '/aml-check',
            'POST',
            $this->payload
        );
        $signedMessage = $signedData[RequestSigner::SIGNED_MESSAGE_KEY];
        $endpointPath = $signedData[RequestSigner::END_POINT_PATH_KEY];
        $messageToSign = 'POST&' . $endpointPath . '&' . $this->payload->getBase64Payload();

        $publicKey = openssl_pkey_get_public($this->publicKey);

        $verify = openssl_verify($messageToSign, base64_decode($signedMessage), $publicKey, OPENSSL_ALGO_SHA256);

        $this->assertEquals(1, $verify);
    }

    /**
     * Dummy request payload
     *
     * @return \Yoti\Http\Payload
     */
    public function getDummyPayload()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);
        return new Payload($amlProfile);
    }
}
