<?php

declare(strict_types=1);

namespace Yoti\Util;

use Yoti\Exception\JsonException;

class Json
{
    /**
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
     * Returns a filtered array without null values
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
     * @throws \Yoti\Exception\JsonException
     */
    private static function validate(): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }
    }
}
