<?php

declare(strict_types=1);

namespace Yoti\Util;

use Yoti\Exception\Base64Exception;

class Base64
{
    private const BASE64_CHARS = '+/';
    private const URL_CHARS = '-_';

    /**
     * @param string $base64UrlValue
     *
     * @return string
     *
     * @throws Base64Exception
     */
    public static function urlDecode(string $base64UrlValue): string
    {
        $value = base64_decode(strtr($base64UrlValue, self::URL_CHARS, self::BASE64_CHARS), true);
        if ($value === false) {
            throw new Base64Exception('Base64 URL value could not be decoded');
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function urlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), self::BASE64_CHARS, self::URL_CHARS), '=');
    }
}
