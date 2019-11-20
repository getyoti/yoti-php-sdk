<?php

namespace Yoti\Util;

class Validation
{
    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isString($value, $name)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("{$name} must be a string");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isBoolean($value, $name)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException("{$name} must be a boolean");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isInteger($value, $name)
    {
        if (!is_integer($value)) {
            throw new \InvalidArgumentException("{$name} must be an integer");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isFloat($value, $name)
    {
        if (!is_float($value)) {
            throw new \InvalidArgumentException("{$name} must be a float");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isNumeric($value, $name)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("{$name} must be numeric");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function notNull($value, $name)
    {
        if (is_null($value)) {
            throw new \InvalidArgumentException("{$name} cannot be null");
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function notEmptyString($value, $name)
    {
        Validation::isString($value, $name);
        if (strlen($value) === 0) {
            throw new \InvalidArgumentException("{$name} cannot be empty");
        }
    }

    /**
     * @param int|float $value
     * @param int|float $limit
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
     * @param int|float $value
     * @param int|float $limit
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
     * @param int|float $value
     * @param int|float $minLimit
     * @param int|float $maxLimit
     * @param string $name
     *
     * @throws \RangeException
     */
    public static function withinRange($value, $minLimit, $maxLimit, $name)
    {
        self::notLessThan($value, $minLimit, $name);
        self::notGreaterThan($value, $maxLimit, $name);
    }

    /**
     * @param array $values
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfIntegers(array $values, $name)
    {
        foreach ($values as $value) {
            if (!is_integer($value)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s must be array of integers',
                    $name
                ));
            }
        }
    }

    /**
     * @param array $values
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfStrings(array $values, $name)
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s must be array of strings',
                    $name
                ));
            }
        }
    }

    /**
     * @param array $values
     * @param array $types
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfType(array $values, array $types, $name)
    {
        foreach ($values as $value) {
            if (!self::isOneOfType($value, $types)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s must be array of %s',
                    $name,
                    implode(', ', $types)
                ));
            }
        }
    }

    /**
     * @param mixed $value
     * @param array $types
     *
     * @return boolean
     */
    private static function isOneOfType($value, array $types)
    {
        foreach ($types as $type) {
            if ($value instanceof $type) {
                return true;
            }
        }
        return false;
    }
}
