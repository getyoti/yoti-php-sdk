<?php

namespace YotiTest\Service\ShareUrl;

use Yoti\ShareUrl\Result;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Result
 */
class ResultTest extends TestCase
{
    const SOME_SHARE_URL = 'https://api.example.com/qr-code';
    const SOME_REF_ID = 'some-ref-id';

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
     *
     * @expectedException \Yoti\Exception\ShareUrlException
     * @expectedExceptionMessage JSON result does not contain 'qrcode'
     */
    public function testInvalidResponseNoQr()
    {
        new Result([
            'ref_id' => self::SOME_REF_ID,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage qrcode must be a string
     */
    public function testInvalidResponseInvalidQr()
    {
        new Result([
            'qrcode' => [self::SOME_SHARE_URL],
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     *
     * @expectedException \Yoti\Exception\ShareUrlException
     * @expectedExceptionMessage JSON result does not contain 'ref_id'
     */
    public function testInvalidResponseNoRefId()
    {
        new Result([
            'qrcode' => self::SOME_SHARE_URL,
        ]);
    }

    /**
     * @covers ::__construct
     * @covers ::getResultValue
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ref_id must be a string
     */
    public function testInvalidResponseInvalidRefId()
    {
        new Result([
            'qrcode' => self::SOME_SHARE_URL,
            'ref_id' => [self::SOME_REF_ID],
        ]);
    }
}
