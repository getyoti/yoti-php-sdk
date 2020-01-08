<?php

namespace Yoti\Util;

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
     */
    public static function stringToDateTime($value)
    {
        Validation::notEmptyString($value, 'value');

        return new \DateTime(
            $value,
            new \DateTimeZone("UTC")
        );
    }
}
