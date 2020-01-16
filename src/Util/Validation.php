<?php

declare(strict_types=1);

namespace Yoti\Util;

class Validation
{
    /**
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isString($value, $name): void
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
    public static function isBoolean($value, $name): void
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
    public static function notNull($value, string $name): void
    {
        if (is_null($value)) {
            throw new \InvalidArgumentException("{$name} cannot be null");
        }
    }

    /**
     * @param string $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function notEmptyString(string $value, string $name): void
    {
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
    public static function notGreaterThan(float $value, float $limit, string $name): void
    {
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
    public static function notLessThan(float $value, float $limit, string $name): void
    {
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
    public static function withinRange($value, $minLimit, $maxLimit, $name): void
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
    public static function isArrayOfIntegers(array $values, $name): void
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
    public static function isArrayOfStrings(array $values, $name): void
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
    public static function isArrayOfType(array $values, array $types, $name): void
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
    private static function isOneOfType($value, array $types): bool
    {
        foreach ($types as $type) {
            if ($value instanceof $type) {
                return true;
            }
        }
        return false;
    }
}
