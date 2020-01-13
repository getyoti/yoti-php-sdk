<?php

namespace YotiTest\Profile\Util;

use Yoti\Profile\Util\EncryptedData;
use YotiTest\TestCase;
use YotiTest\TestData;

/**
 * @coversDefaultClass \Yoti\Profile\Util\EncryptedData
 */
class EncrypedDataTest extends TestCase
{
    const SOME_DATA = 'some data';

    /**
     * @var string
     */
    private $pem;

    /**
     * @var string
     */
    private $wrappedKey;

    /**
     * Setup test data.
     */
    public function setup(): void
    {
        $this->pem = file_get_contents(TestData::PEM_FILE);
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
            $this->pem
        );

        $this->assertEquals(self::SOME_DATA, $decryptedData);
    }

    /**
     * @covers ::decryptFromProto
     */
    public function testDecryptFromProto()
    {
        $decryptedData = EncryptedData::decryptFromProto(
            $this->encryptedDataProto,
            $this->wrappedKey,
            $this->pem
        );

        $this->assertEquals(self::SOME_DATA, $decryptedData);
    }

    /**
     * @return \Yoti\Protobuf\Compubapi\EncryptedData
     */
    private function createEncryptedDataProto()
    {
        openssl_private_decrypt(
            base64_decode($this->wrappedKey),
            $unwrappedKey,
            file_get_contents(TestData::PEM_FILE)
        );

        $iv = random_bytes(16);

        return new \Yoti\Protobuf\Compubapi\EncryptedData([
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
