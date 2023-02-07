<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

class RequestedIdDocumentComparisonCheckBuilder
{
    public function build(): RequestedIdDocumentComparisonCheck
    {
        return new RequestedIdDocumentComparisonCheck();
    }
}
