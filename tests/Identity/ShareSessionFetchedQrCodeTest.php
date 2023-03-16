<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\Extension\Extension;
use Yoti\Identity\ShareSessionCreated;
use Yoti\Identity\ShareSessionFetchedQrCode;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionFetchedQrCode
 */
class ShareSessionFetchedQrCodeTest extends TestCase
{
    private const SOME_ID = 'some_id';
    private const SOME_EXPIRY = 'some_expiry';
    private const SOME_POLICY = 'some_policy';
    private const SOME_REDIRECT_URI = 'some_redirect_uri';

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getExpiry
     * @covers ::getRedirectUri
     * @covers ::getPolicy
     * @covers ::getExtensions
     * @covers ::getSession
     */
    public function testShouldBuildCorrectly()
    {
        $extensions = [
            ['type' => 'some', 'content' => 'content'],
            ['type' => 'some2', 'content' => 'content2'],
        ];

        $shareSession = [
            'id' => 'some',
            'status' => 'status',
            'expiry' => 'expiry',
        ];

        $qrCode = new ShareSessionFetchedQrCode([
            'id' => self::SOME_ID,
            'expiry' => self::SOME_EXPIRY,
            'policy' => self::SOME_POLICY,
            'extensions' => $extensions,
            'session' => $shareSession,
            'redirectUri' => self::SOME_REDIRECT_URI,
        ]);

        $expected = [
            'id' => self::SOME_ID,
            'expiry' => self::SOME_EXPIRY,
            'policy' => self::SOME_POLICY,
            'extensions' => $extensions,
            'session' => $shareSession,
            'redirectUri' => self::SOME_REDIRECT_URI,
        ];

        $this->assertInstanceOf(ShareSessionFetchedQrCode::class, $qrCode);

        $this->assertEquals(self::SOME_ID, $qrCode->getId());
        $this->assertEquals(self::SOME_EXPIRY, $qrCode->getExpiry());
        $this->assertEquals(self::SOME_POLICY, $qrCode->getPolicy());
        $this->assertEquals(self::SOME_REDIRECT_URI, $qrCode->getRedirectUri());

        $this->assertInstanceOf(ShareSessionCreated::class, $qrCode->getSession());

        $this->assertContainsOnlyInstancesOf(Extension::class, $qrCode->getExtensions());

        $this->assertEquals(self::SOME_REDIRECT_URI, $qrCode->getRedirectUri());

        $this->assertEquals(json_encode($expected), json_encode($qrCode));
    }
}
