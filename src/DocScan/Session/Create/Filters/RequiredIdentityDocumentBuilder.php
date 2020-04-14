<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredIdentityDocumentBuilder extends RequiredDocumentBuilder
{
    /**
     * @inheritDoc
     */
    public function build(): RequiredIdentityDocument
    {
        return new RequiredIdentityDocument($this->filter);
    }
}
