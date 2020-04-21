<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\DocScan\Constants;

class RequiredIdentityDocument extends RequiredDocument
{
    /**
     * @var RequiredDocumentFilter|null
     */
    private $filter;

    /**
     * @param RequiredDocumentFilter|null $filter
     */
    public function __construct(?RequiredDocumentFilter $filter)
    {
        parent::__construct(Constants::ID_DOCUMENT);

        $this->filter = $filter;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        $jsonData = parent::jsonSerialize();

        if (isset($this->filter)) {
            $jsonData->filter = $this->filter;
        }

        return $jsonData;
    }
}
