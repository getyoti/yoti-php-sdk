<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source;

use Yoti\DocScan\Constants;

class RelyingBusinessAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::RELYING_BUSINESS;
    }
}
