<?php

namespace Yoti\IDV\Session\Retrieve\Configuration\Capture\Source;

use Yoti\IDV\Constants;

class IbvAllowedSourceResponse extends AllowedSourceResponse
{
    public function __construct()
    {
        $this->type = Constants::IBV;
    }
}
