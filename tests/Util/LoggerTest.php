<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Yoti\Test\TestCase;
use Yoti\Util\Logger;

/**
 * @coversDefaultClass \Yoti\Util\Logger
 */
class LoggerTest extends TestCase
{
    private const SOME_MESSAGE = 'some message';
    private const SOME_INVALID_LEVEL = 'invalid level';

    /**
     * @covers ::log
     */
    public function testLog()
    {
        $this->captureExpectedLogs();

        $logger = new Logger();
        $logger->log(LogLevel::ERROR, self::SOME_MESSAGE);

        $this->assertLogContains(sprintf('error: %s', self::SOME_MESSAGE));
    }

    /**
     * @covers ::log
     */
    public function testLogInvalidLevel()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('"%s" level is not allowed', self::SOME_INVALID_LEVEL));

        $logger = new Logger();
        $logger->log(self::SOME_INVALID_LEVEL, self::SOME_MESSAGE);
    }
}
