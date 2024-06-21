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
     * @param mixed $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function notEmptyString($value, string $name): void
    {
        Validation::isString($value, $name);
        if (strlen($value) === 0) {
            throw new \InvalidArgumentException("{$name} cannot be empty");
        }
    }

    /**
     * @param mixed[] $value
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function notEmptyArray(array $value, string $name): void
    {
        if (count($value) === 0) {
            throw new \InvalidArgumentException("{$name} cannot be empty");
        }
    }

    /**
     * @param float $value
     * @param float $limit
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
     * @param float $value
     * @param float $limit
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
     * @param float $value
     * @param float $minLimit
     * @param float $maxLimit
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
     * @param mixed[] $values
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfIntegers(array $values, string $name): void
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
     * @param mixed[] $values
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfStrings(array $values, string $name): void
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
     * @param mixed[] $values
     * @param string[] $types
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function isArrayOfType(array $values, array $types, string $name): void
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
     * @param string[] $types
     *
     * @return bool
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

    /**
     * @param mixed $value
     * @param string $pattern
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public static function matchesPattern($value, $pattern, $name): void
    {
        if (preg_match($pattern, $value) !== 1) {
            throw new \InvalidArgumentException("'{$name}' value '{$value}' does not match format '{$pattern}'");
        }
    }
}
