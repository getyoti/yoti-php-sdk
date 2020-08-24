<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

class RequestedIdDocumentComparisonCheckBuilder
{

    public function build(): RequestedIdDocumentComparisonCheck
    {
        return new RequestedIdDocumentComparisonCheck();
    }
}
