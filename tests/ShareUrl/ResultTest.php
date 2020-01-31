<?php

declare(strict_types=1);

namespace Yoti\Test\Service\ShareUrl;

use Yoti\ShareUrl\Result;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Result
 */
class ResultTest extends TestCase
{
    private const SOME_SHARE_URL = 'https://api.example.com/qr-code';
    private const SOME_REF_ID = 'some-ref-id';

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     * @covers ::getShareUrl
     * @covers ::getRefId
     */
    public function testValidResponse()
    {
        $result = new Result([
            'qrcode' => self::SOME_SHARE_URL,
            'ref_id' => self::SOME_REF_ID,
        ]);

        $this->assertEquals(self::SOME_SHARE_URL, $result->getShareUrl());
        $this->assertEquals(self::SOME_REF_ID, $result->getRefId());
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     */
    public function testInvalidResponseNoQr()
    {
        $this->expectException(\Yoti\Exception\ShareUrlException::class);
        $this->expectExceptionMessage('JSON result does not contain \'qrcode\'');

        new Result([
            'ref_id' => self::SOME_REF_ID,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     */
    public function testInvalidResponseInvalidQr()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('qrcode must be a string');

        new Result([
            'qrcode' => [self::SOME_SHARE_URL],
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     */
    public function testInvalidResponseNoRefId()
    {
        $this->expectException(\Yoti\Exception\ShareUrlException::class);
        $this->expectExceptionMessage('JSON result does not contain \'ref_id\'');

        new Result([
            'qrcode' => self::SOME_SHARE_URL,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     */
    public function testInvalidResponseInvalidRefId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ref_id must be a string');

        new Result([
            'qrcode' => self::SOME_SHARE_URL,
            'ref_id' => [self::SOME_REF_ID],
        ]);
    }
}
