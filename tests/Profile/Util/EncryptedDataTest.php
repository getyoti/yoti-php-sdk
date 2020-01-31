<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Util;

use Yoti\Exception\EncryptedDataException;
use Yoti\Profile\Util\EncryptedData;
use Yoti\Protobuf\Compubapi\EncryptedData as EncryptedDataProto;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Profile\Util\EncryptedData
 */
class EncrypedDataTest extends TestCase
{
    private const SOME_DATA = 'some data';

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var string
     */
    private $wrappedKey;

    /**
     * Setup test data.
     */
    public function setup(): void
    {
        $this->pemFile = PemFile::fromFilePath(TestData::PEM_FILE);
        $receiptArr = json_decode(file_get_contents(TestData::RECEIPT_JSON), true);
        $this->wrappedKey = $receiptArr['receipt']['wrapped_receipt_key'];
        $this->encryptedDataProto = $this->createEncryptedDataProto();
    }

    /**
     * @covers ::decrypt
     */
    public function testDecrypt()
    {
        $decryptedData = EncryptedData::decrypt(
            base64_encode($this->encryptedDataProto->serializeToString()),
            $this->wrappedKey,
            $this->pemFile
        );

        $this->assertEquals(self::SOME_DATA, $decryptedData);
    }

    /**
     * @covers ::decrypt
     */
    public function testDecryptInvalid()
    {
        $this->expectException(EncryptedDataException::class);
        $this->expectExceptionMessage('Could not decrypt data');

        EncryptedData::decrypt(
            base64_encode(
                (new EncryptedDataProto([
                    'cipher_text' => 'some-invalid-text',
                    'iv' => random_bytes(16),
                ]))->serializeToString()
            ),
            $this->wrappedKey,
            $this->pemFile
        );
    }

    /**
     * @covers ::decrypt
     */
    public function testDecryptDecodeError()
    {
        $this->expectException(EncryptedDataException::class);
        $this->expectExceptionMessage('Could not decode data');

        EncryptedData::decrypt(
            'some-invalid-string',
            $this->wrappedKey,
            $this->pemFile
        );
    }

    /**
     * @covers ::decrypt
     */
    public function testDecryptDecodeWrappedKeyError()
    {
        $this->expectException(EncryptedDataException::class);
        $this->expectExceptionMessage('Could not decode wrapped key');

        EncryptedData::decrypt(
            base64_encode($this->encryptedDataProto->serializeToString()),
            'some-invalid-key',
            $this->pemFile
        );
    }

    /**
     * @return \Yoti\Protobuf\Compubapi\EncryptedData
     */
    private function createEncryptedDataProto(): EncryptedDataProto
    {
        openssl_private_decrypt(
            base64_decode($this->wrappedKey),
            $unwrappedKey,
            file_get_contents(TestData::PEM_FILE)
        );

        $iv = random_bytes(16);

        return new EncryptedDataProto([
            'cipher_text' => openssl_encrypt(
                self::SOME_DATA,
                'aes-256-cbc',
                $unwrappedKey,
                OPENSSL_RAW_DATA,
                $iv
            ),
            'iv' => $iv,
        ]);
    }
}
