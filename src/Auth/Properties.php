<?php

declare(strict_types=1);

namespace Yoti\Auth;

/**
 * Contains property values used by the auth module for
 * creating Yoti Authentication tokens.
 *
 * Mirrors the Java SDK's com.yoti.auth.Properties class.
 */
final class Properties
{
    /**
     * The default Yoti authentication service host.
     */
    private const YOTI_AUTH_HOST = 'https://auth.api.yoti.com';

    /**
     * The default Yoti authentication service path.
     */
    private const YOTI_AUTH_PATH_PREFIX = '/v1/oauth/token';

    /**
     * The default Yoti authentication URL.
     */
    public const DEFAULT_YOTI_AUTH_URL = self::YOTI_AUTH_HOST . self::YOTI_AUTH_PATH_PREFIX;

    /**
     * Environment variable key for overriding the auth URL.
     */
    public const ENV_YOTI_AUTH_URL = 'YOTI_AUTH_URL';

    private function __construct()
    {
        // Prevent instantiation.
    }
}
