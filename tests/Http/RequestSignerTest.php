<?php

declare(strict_types=1);

namespace Yoti\Test\Http;

use Yoti\Aml\Address;
use Yoti\Aml\Country;
use Yoti\Aml\Profile;
use Yoti\Http\Payload;
use Yoti\Http\RequestSigner;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Http\RequestSigner
 */
class RequestSignerTest extends TestCase
{
    private const SOME_PATH = '/some-path';

    private const SOME_METHOD = 'POST';

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

    public function setup(): void
    {
        $this->pem = file_get_contents(TestData::AML_PRIVATE_KEY);
        $this->publicKey = file_get_contents(TestData::AML_PUBLIC_KEY);
        $this->payload = $this->getDummyPayload();
    }

    /**
     * @covers ::sign
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
     */
    public function testValidateSignedMessage()
    {
        $this->expectException(\Yoti\Http\Exception\RequestSignerException::class);
        $this->expectExceptionMessage('Could not sign request');

        $this->captureExpectedLogs();

        $somePemFile = $this->createMock(PemFile::class);
        $somePemFile
            ->method('__toString')
            ->willReturn(TestData::INVALID_PEM_FILE);

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
        $amlAddress = new Address(new Country('GBR'));
        $amlProfile = new Profile('Edward Richard George', 'Heath', $amlAddress);
        return Payload::fromJsonData($amlProfile);
    }
}
