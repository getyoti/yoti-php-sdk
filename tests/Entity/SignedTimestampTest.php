<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\SignedTimeStamp;

/**
 * @coversDefaultClass \Yoti\Entity\SignedTimeStamp
 */
class SignedTimeStampTest extends TestCase
{
    const SOME_VERSION = 'some_version';

    /**
     * @var Yoti\Entity\SignedTimeStamp
     */
    private $signedTimestamp;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * Create SignedTimeStamp.
     */
    public function setup()
    {
        $this->timestamp = new \DateTime();
        $this->signedTimestamp =  new SignedTimeStamp(
            self::SOME_VERSION,
            $this->timestamp
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getTimestamp
     */
    public function testGetTimestamp()
    {
        $this->assertSame($this->timestamp, $this->signedTimestamp->getTimestamp());
    }

    /**
     * @covers ::__construct
     * @covers ::getVersion
     */
    public function testGetVersion()
    {
        $this->assertSame(self::SOME_VERSION, $this->signedTimestamp->getVersion());
    }
}
