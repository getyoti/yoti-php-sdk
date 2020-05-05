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
            return (string) $_SERVER[$name];
        }

        return null;
    }
}
