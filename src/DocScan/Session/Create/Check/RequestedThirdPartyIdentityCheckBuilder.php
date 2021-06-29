<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

class RequestedThirdPartyIdentityCheckBuilder
{
    public function build(): RequestedThirdPartyIdentityCheck
    {
        return new RequestedThirdPartyIdentityCheck();
    }
}
