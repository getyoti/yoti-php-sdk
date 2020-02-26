<?php

declare(strict_types=1);

namespace Yoti\Util;

use Yoti\Exception\DateTimeException;

class DateTime
{
    /**
     * RFC3339 format with microseconds.
     */
    public const RFC3339 = 'Y-m-d\TH:i:s.uP';

    /**
     * @param string $value
     *
     * @return \DateTime
     *
     * @throws \Yoti\Exception\DateTimeException
     */
    public static function stringToDateTime(string $value): \DateTime
    {
        Validation::notEmptyString($value, 'value');

        try {
            return new \DateTime(
                $value,
                new \DateTimeZone("UTC")
            );
        } catch (\Exception $e) {
            throw new DateTimeException('Could not parse string to DateTime', 0, $e);
        }
    }

    /**
     * Extract the microseconds from provided timestamp.
     *
     * @param int $timestamp
     *
     * @return int
     */
    private static function extractMicrosecondsFromTimestamp(int $timestamp): int
    {
        $microseconds = $timestamp % 1000000;
        if ($microseconds < 0) {
            return $microseconds + 1000000;
        }
        return $microseconds;
    }

    /**
     * @param int $timestamp
     *
     * @return \DateTime
     */
    public static function timestampToDateTime(int $timestamp): \DateTime
    {
        $seconds = floor($timestamp / 1000000);
        $microSeconds = self::extractMicrosecondsFromTimestamp($timestamp);
        $zeroPaddedMicroSeconds = str_pad((string) $microSeconds, 6, '0', STR_PAD_LEFT);

        $formattedTimestamp = sprintf(
            '%d.%s',
            $seconds,
            $zeroPaddedMicroSeconds
        );

        return static::createFromFormat('U.u', $formattedTimestamp);
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return int
     */
    public static function dateTimeToTimestamp(\DateTime $dateTime): int
    {
        return ($dateTime->getTimestamp() * 1000000) + (int) $dateTime->format('u');
    }

    /**
     * @param string $format
     * @param string $time
     *
     * @uses \DateTime::createFromFormat() throws exception on failure.
     *
     * @return \DateTime
     *
     * @throws \Yoti\Exception\DateTimeException
     */
    public static function createFromFormat(string $format, string $time): \DateTime
    {
        // Format DateTime to include microseconds and timezone
        $dateTime = \DateTime::createFromFormat(
            $format,
            $time,
            new \DateTimeZone('UTC')
        );

        if ($dateTime === false) {
            throw new DateTimeException('Could not parse from format');
        }

        return $dateTime;
    }
}
