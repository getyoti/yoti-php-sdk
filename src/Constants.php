<?php

declare(strict_types=1);

namespace Yoti;

class Constants
{
    /** Default API base URL */
    const API_BASE_URL = 'https://api.yoti.com';

    /** Default API URL */
    const API_URL = self::API_BASE_URL . '/api/v1';

    /** Default SDK identifier */
    const SDK_IDENTIFIER = 'PHP';

    /** Default SDK version */
    const SDK_VERSION = '3.0.0';

    /** Base url for connect page (user will be redirected to this page eg. baseurl/app-id) */
    const CONNECT_BASE_URL = 'https://www.yoti.com/connect';

    /** Yoti Hub login */
    const DASHBOARD_URL = 'https://hub.yoti.com';
}
