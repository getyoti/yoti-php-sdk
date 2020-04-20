<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\DocScan\Constants;

class RequiredIdentityDocument extends RequiredDocument
{
    /**
     * @param RequiredDocumentFilter|null $filter
     */
    public function __construct(?RequiredDocumentFilter $filter)
    {
        parent::__construct(Constants::ID_DOCUMENT, $filter);
    }
}
