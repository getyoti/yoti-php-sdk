<?php

declare(strict_types=1);

namespace Yoti\Util;

class Env
{
    /**
     * Get environment variable.
     *
     * @param string $name
     * @return string|null
     */
    public static function get(string $name): ?string
    {
        if (isset($_SERVER[$name])) {
            $value = $_SERVER[$name];

            if (is_string($value) && strlen($value) === 0) {
                return null;
            }

            return (string) $value;
        }

        return null;
    }
}
