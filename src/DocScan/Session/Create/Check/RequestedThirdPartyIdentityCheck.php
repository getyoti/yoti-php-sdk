<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedThirdPartyIdentityCheck extends RequestedCheck
{
    /**
     * @return string
     */
    protected function getType(): string
    {
        return Constants::THIRD_PARTY_IDENTITY;
    }

    /**
     * @return RequestedCheckConfigInterface|null
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return null;
    }
}
