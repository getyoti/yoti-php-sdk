<?php

namespace YotiTest\Profile\Attribute;

use Yoti\Profile\Attribute\SignedTimestamp;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Attribute\SignedTimestamp
 */
class SignedTimeStampTest extends TestCase
{
    const SOME_VERSION = 'some_version';

    /**
     * @var Yoti\Profile\Attribute\SignedTimestamp
     */
    private $signedTimestamp;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * Create SignedTimeStamp.
     */
    public function setup(): void
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
