<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Attribute;

use Yoti\Profile\Attribute\SignedTimestamp;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Attribute\SignedTimestamp
 */
class SignedTimeStampTest extends TestCase
{
    private const SOME_VERSION = 123;

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
