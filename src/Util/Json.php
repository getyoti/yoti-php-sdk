<?php

declare(strict_types=1);

namespace Yoti\Util;

use Yoti\Exception\JsonException;

class Json
{
    /**
     * Decodes a JSON string.
     *
     * @param string $json
     * @param bool $assoc
     *
     * @return mixed
     */
    public static function decode($json, $assoc = true)
    {
        $jsonDecoded = json_decode($json, $assoc);
        self::validate();
        return $jsonDecoded;
    }

    /**
     * Encodes data into a JSON string.
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function encode($data): string
    {
        $jsonEncoded = json_encode($data);
        self::validate();
        return (string) $jsonEncoded;
    }

    /**
     * Returns a filtered array without null values.
     *
     * @param array<mixed, mixed> $data
     * @return array<mixed, mixed>
     */
    public static function withoutNullValues(array $data): array
    {
        return array_filter($data, function ($value): bool {
            return $value !== null;
        });
    }

    /**
     * Validates the JSON encoding process.
     *
     * @throws \Yoti\Exception\JsonException
     */
    private static function validate(): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }
    }

    /**
     * Converts data from Latin1 to UTF-8 recursively.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function convertFromLatin1ToUtf8Recursively($data)
    {
        if (is_string($data)) {
            return utf8_encode($data);
        } elseif (is_array($data)) {
            // Iterate over array elements
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = self::convertFromLatin1ToUtf8Recursively($value);
            }
            return $result;
        } elseif (is_object($data)) {
            // Convert object to array and iterate over its elements
            $data = (array) $data;
            foreach ($data as $key => $value) {
                $data[$key] = self::convertFromLatin1ToUtf8Recursively($value);
            }
            return (object) $data;
        } else {
            return $data;
        }
    }
}
