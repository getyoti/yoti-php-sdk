<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Traits\Builder\ManualCheckTrait;

class RequestedDocumentAuthenticityCheckBuilder
{
    use ManualCheckTrait;

    public function build(): RequestedDocumentAuthenticityCheck
    {
        $config = new RequestedDocumentAuthenticityCheckConfig($this->manualCheck);
        return new RequestedDocumentAuthenticityCheck($config);
    }
}
