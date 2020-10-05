<?php

declare(strict_types=1);

namespace Yoti;

class Constants
{
    /** Default API base URL */
    public const API_BASE_URL = 'https://api.yoti.com';

    /** Default API URL */
    public const API_URL = self::API_BASE_URL . '/api/v1';

    /** Environment variable to override the default API URL */
    public const ENV_API_URL = 'YOTI_API_URL';

    /** Default Doc Scan API URL */
    public const DOC_SCAN_API_URL = self::API_BASE_URL . '/idverify/v1';

    /** Environment variable to override the default Doc Scan API URL */
    public const ENV_DOC_SCAN_API_URL = 'YOTI_DOC_SCAN_API_URL';

    /** Default SDK identifier */
    public const SDK_IDENTIFIER = 'PHP';

    /** Default SDK version */
    public const SDK_VERSION = '3.5.0';

    /** Base url for connect page (user will be redirected to this page eg. baseurl/app-id) */
    public const CONNECT_BASE_URL = 'https://www.yoti.com/connect';

    /** Yoti Hub login */
    public const DASHBOARD_URL = 'https://hub.yoti.com';
}
