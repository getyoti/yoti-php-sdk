<?php

declare(strict_types=1);

namespace Yoti\Util;

use Yoti\Exception\DateTimeException;

class DateTime
{
    /**
     * RFC3339 format with microseconds.
     */
    const RFC3339 = 'Y-m-d\TH:i:s.uP';

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
     * @param int $timestamp
     *
     * @return \DateTime
     */
    public static function timestampToDateTime(int $timestamp): \DateTime
    {
        // Format DateTime to include microseconds and timezone
        $timeIncMicroSeconds = number_format($timestamp / 1000000, 6, '.', '');
        return static::createFromFormat('U.u', $timeIncMicroSeconds);
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
