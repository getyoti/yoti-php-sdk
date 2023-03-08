<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\ShareSessionCreatedQrCode;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionCreatedQrCode
 */
class ShareSessionCreatedQrCodeTest extends TestCase
{
    private const SOME_ID = 'some_id';
    private const SOME_URI = 'some_uri';

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getUri
     */
    public function testShouldBuildCorrectly()
    {
        $qrCode = new ShareSessionCreatedQrCode([
           'id' => self::SOME_ID,
           'uri' => self::SOME_URI,
           'failed' => 'failed'
        ]);

        $expected = [
            'id' => self::SOME_ID,
            'uri' => self::SOME_URI,
        ];

        $this->assertInstanceOf(ShareSessionCreatedQrCode::class, $qrCode);

        $this->assertEquals(self::SOME_ID, $qrCode->getId());
        $this->assertEquals(self::SOME_URI, $qrCode->getUri());

        $this->assertEquals(json_encode($expected), json_encode($qrCode));
    }
}
