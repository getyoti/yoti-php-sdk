<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredIdentityDocumentBuilder extends RequiredDocumentBuilder
{
    /**
     * @inheritDoc
     */
    public function build(): RequiredDocument
    {
        return new RequiredIdentityDocument($this->filter);
    }
}
