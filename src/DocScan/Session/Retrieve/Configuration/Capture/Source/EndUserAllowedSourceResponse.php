<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source;

use Yoti\DocScan\Constants;

class EndUserAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::END_USER;
    }
}
