<?php

declare(strict_types=1);

namespace Yoti\Util;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

/**
 * Provides default logger.
 */
class Logger extends AbstractLogger
{
    private const LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        if (!in_array($level, self::LEVELS, true)) {
            throw new InvalidArgumentException(sprintf('"%s" level is not allowed', $level));
        }

        error_log(sprintf('%s: %s', $level, $message), 0);
    }
}
