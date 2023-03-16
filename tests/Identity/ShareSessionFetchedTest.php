<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\ShareSessionFetched;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionFetched
 */
class ShareSessionFetchedTest extends TestCase
{
    private const SOME_ID = 'SOME_ID';
    private const SOME_STATUS = 'SOME_STATUS';
    private const SOME_EXPIRY = 'SOME_EXPIRY';
    private const SOME_CREATED = 'SOME_CREATED';
    private const SOME_UPDATED = 'SOME_UPDATED';
    private const SOME_QRCODE_ID = '1';
    private const SOME_RECEIPT_ID = '2';


    /**
     * @covers ::getExpiry
     * @covers ::getStatus
     * @covers ::getId
     * @covers ::getCreated
     * @covers ::getQrCodeId
     * @covers ::getReceiptId
     * @covers ::getUpdated
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function testShouldBuildCorrectly()
    {
        $shareSession = new ShareSessionFetched([
            'id' => self::SOME_ID,
            'status' => self::SOME_STATUS,
            'expiry' => self::SOME_EXPIRY,
            'created' => self::SOME_CREATED,
            'updated' => self::SOME_UPDATED,
            'failed' => 'SQL injection',
            'qrCode' => ['id' => self::SOME_QRCODE_ID],
            'receipt' => ['id' => self::SOME_RECEIPT_ID],
        ]);

        $expected = [
            'id' => self::SOME_ID,
            'status' => self::SOME_STATUS,
            'expiry' => self::SOME_EXPIRY,
            'created' => self::SOME_CREATED,
            'updated' => self::SOME_UPDATED,
            'qrCodeId' => self::SOME_QRCODE_ID,
            'receiptId' => self::SOME_RECEIPT_ID,
        ];

        $this->assertInstanceOf(ShareSessionFetched::class, $shareSession);
        $this->assertEquals(self::SOME_ID, $shareSession->getId());
        $this->assertEquals(self::SOME_STATUS, $shareSession->getStatus());
        $this->assertEquals(self::SOME_EXPIRY, $shareSession->getExpiry());
        $this->assertEquals(self::SOME_CREATED, $shareSession->getCreated());
        $this->assertEquals(self::SOME_UPDATED, $shareSession->getUpdated());
        $this->assertEquals(self::SOME_QRCODE_ID, $shareSession->getQrCodeId());
        $this->assertEquals(self::SOME_RECEIPT_ID, $shareSession->getReceiptId());
        $this->assertEquals(json_encode($expected), json_encode($shareSession));
    }
}
