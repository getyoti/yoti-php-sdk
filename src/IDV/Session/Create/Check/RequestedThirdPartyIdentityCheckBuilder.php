<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

class RequestedThirdPartyIdentityCheckBuilder
{
    public function build(): RequestedThirdPartyIdentityCheck
    {
        return new RequestedThirdPartyIdentityCheck();
    }
}
