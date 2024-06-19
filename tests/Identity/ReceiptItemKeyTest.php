<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\ReceiptItemKey;
use Yoti\Test\TestCase;

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
        $someIv = 'd2VvZm13cmZtd3JrZm13ZXBrZm13cHJla21m';
        $someValue = 'ZDJWdlptMTNjbVp0ZDNKclptMTNaWEJyWm0xM2NISmxhMjFt';

        $sessionData = [
            'id' => $someId,
            'iv' => $someIv,
            'value' => $someValue
        ];

        $receiptItemKey = new ReceiptItemKey($sessionData);

        $this->assertEquals($someId, $receiptItemKey->getId());
        $this->assertEquals($someValue, $receiptItemKey->getValue());
    }
}
