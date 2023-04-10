<?php

namespace Yoti\Test\Identity;

use Yoti\Exception\EncryptedDataException;
use Yoti\Identity\ReceiptItemKey;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;

/**
 * @coversDefaultClass \Yoti\Identity\ReceiptItemKey
 */
class ReceiptItemKeyTest extends TestCase
{
    /**
     * @covers ::getId
     * @covers ::getIv
     * @covers ::getValue
     * @covers ::setIv
     * @covers ::__construct
     */
    public function testShouldBuildCorrectly()
    {
        $someId = 'SOME_ID';
        $someIv = TestData::YOTI_CONNECT_TOKEN_DECRYPTED;
        $someValue = 'weofmwrfmwrkfmwepkfmwprekmf';

        $sessionData = [
            'id' => $someId,
            'iv' => $someIv,
            'value' => base64_encode($someValue)
        ];

        $this->expectException(EncryptedDataException::class);

        $receiptItemKey = new ReceiptItemKey($sessionData);

        $this->assertEquals($someId, $receiptItemKey->getId());
        $this->assertEquals($someValue, $receiptItemKey->getValue());
    }
}
