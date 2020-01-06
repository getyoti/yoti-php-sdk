<?php

namespace Yoti\Util;

use Yoti\Exception\DateTimeException;

class DateTime
{
    /**
     * RFC3339 format with microseconds.
     */
    const RFC3339 = 'Y-m-d\TH:i:s.uP';

    /**
     * @param $value
     *
     * @return \DateTime
     *
     * @throws \Yoti\Exception\DateTimeException
     */
    public static function stringToDateTime($value): \DateTime
    {
        Validation::notEmptyString($value, 'value');

        try {
            return new \DateTime(
                $value,
                new \DateTimeZone("UTC")
            );
        } catch (\Exception $e) {
            throw new DateTimeException('Could not parse string to DateTime', null, $e);
        }
    }
}
