<?php

namespace Yoti\Util;

class Validation
{
    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function isString($value, $name)
    {
        if (!is_string($value)) {
            throw new \TypeError("{$name} must be a string");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function isBoolean($value, $name)
    {
        if (!is_bool($value)) {
            throw new \TypeError("{$name} must be a boolean");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function isInteger($value, $name)
    {
        if (!is_integer($value)) {
            throw new \TypeError("{$name} must be an integer");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function isFloat($value, $name)
    {
        if (!is_float($value)) {
            throw new \TypeError("{$name} must be a float");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function isNumeric($value, $name)
    {
        if (!is_numeric($value)) {
            throw new \TypeError("{$name} must be numeric");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \TypeError
     */
    public static function notNull($value, $name)
    {
        if (is_null($value)) {
            throw new \TypeError("{$name} cannot be null");
        }
    }

    /**
     * @param integer|float $value
     * @param integer|float $limit
     * @param string $name
     *
     * @throws \RangeException
     */
    public static function notGreaterThan($value, $limit, $name)
    {
        self::isNumeric($value, $name);
        if ($value > $limit) {
            throw new \RangeException("'{$name}' value '{$value}' is greater than '{$limit}'");
        }
    }

    /**
     * @param integer|float $value
     * @param integer|float $limit
     * @param string $name
     *
     * @throws \RangeException
     */
    public static function notLessThan($value, $limit, $name)
    {
        self::isNumeric($value, $name);
        if ($value < $limit) {
            throw new \RangeException("'{$name}' value '{$value}' is less than '{$limit}'");
        }
    }

    /**
     * @param integer|float $value
     * @param integer|float $minLimit
     * @param integer|float $maxLimit
     * @param string $name
     *
     * @throws \RangeException
     */
    public static function withinRange($value, $minLimit, $maxLimit, $name)
    {
        self::notLessThan($value, $minLimit, $name);
        self::notGreaterThan($value, $maxLimit, $name);
    }
}
