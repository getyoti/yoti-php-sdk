<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Constants;

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
