<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source;

use Yoti\DocScan\Constants;

class IbvAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::IBV;
    }
}
