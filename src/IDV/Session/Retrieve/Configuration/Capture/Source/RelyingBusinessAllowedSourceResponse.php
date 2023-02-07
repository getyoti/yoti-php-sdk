<?php

namespace Yoti\IDV\Session\Retrieve\Configuration\Capture\Source;

use Yoti\IDV\Constants;

class RelyingBusinessAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::RELYING_BUSINESS;
    }
}
