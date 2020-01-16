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
}
