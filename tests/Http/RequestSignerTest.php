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
    const SOME_PATH = '/some-path';

    const SOME_METHOD = 'POST';

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
     * @covers ::validateSignedMessage
     */
    public function testSign()
    {
        $signedMessage = RequestSigner::sign(
            PemFile::fromString($this->pem),
            self::SOME_PATH,
            self::SOME_METHOD,
            $this->payload
        );
        $messageToSign = self::SOME_METHOD . '&' . self::SOME_PATH . '&' . $this->payload->toBase64();

        $publicKey = openssl_pkey_get_public($this->publicKey);

        $verify = openssl_verify($messageToSign, base64_decode($signedMessage), $publicKey, OPENSSL_ALGO_SHA256);

        $this->assertEquals(1, $verify);
    }

    /**
     * @covers ::sign
     * @covers ::validateSignedMessage
     *
     * @expectedException \Yoti\Http\Exception\RequestSignerException
     * @expectedExceptionMessage Could not sign request
     */
    public function testValidateSignedMessage()
    {
        $this->captureExpectedLogs();

        $somePemFile = $this->createMock(PemFile::class);
        $somePemFile
            ->method('__toString')
            ->willReturn(INVALID_PEM_FILE);

        RequestSigner::sign(
            $somePemFile,
            self::SOME_PATH,
            self::SOME_METHOD,
            $this->payload
        );

        $this->assertLogContains('supplied key param cannot be coerced into a private key');
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
        return Payload::fromJsonData($amlProfile);
    }
}
