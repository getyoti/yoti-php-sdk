<?php

namespace Yoti\Util;

class Constants
{
    const SDK_VERSION = '3.0.0';

    /**
     * RFC3339 format with microseconds.
     *
     * This will be replaced by \DateTime::RFC3339_EXTENDED
     * once PHP 5.6 is no longer supported.
     */
    const DATE_FORMAT_RFC3339 = 'Y-m-d\TH:i:s.uP';
}
