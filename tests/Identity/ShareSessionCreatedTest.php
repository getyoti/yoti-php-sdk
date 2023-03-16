<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\ShareSessionCreated;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionCreated
 */
class ShareSessionCreatedTest extends TestCase
{
    private const SOME_ID = 'SOME_ID';
    private const SOME_STATUS = 'SOME_STATUS';
    private const SOME_EXPIRY = 'SOME_EXPIRY';

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getExpiry
     * @covers ::getStatus
     */
    public function testShouldBuildCorrectly()
    {
        $shareSession = new ShareSessionCreated([
            'id' => self::SOME_ID,
            'status' => self::SOME_STATUS,
            'expiry' => self::SOME_EXPIRY,
            'failed' => 'SQL injection'
        ]);

        $expected = [
            'id' => self::SOME_ID,
            'status' => self::SOME_STATUS,
            'expiry' => self::SOME_EXPIRY,
        ];

        $this->assertInstanceOf(ShareSessionCreated::class, $shareSession);
        $this->assertEquals(self::SOME_ID, $shareSession->getId());
        $this->assertEquals(self::SOME_STATUS, $shareSession->getStatus());
        $this->assertEquals(self::SOME_EXPIRY, $shareSession->getExpiry());
        $this->assertEquals(json_encode($expected), json_encode($shareSession));
    }
}
