<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

class RequestedDocumentAuthenticityCheckBuilder
{

    public function build(): RequestedDocumentAuthenticityCheck
    {
        return new RequestedDocumentAuthenticityCheck();
    }
}
