<?php

namespace Yoti\IDV\Session\Retrieve\Configuration\Capture\Source;

use Yoti\IDV\Constants;

class EndUserAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::END_USER;
    }
}
