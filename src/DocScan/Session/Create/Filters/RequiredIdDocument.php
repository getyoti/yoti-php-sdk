<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\DocScan\Constants;

class RequiredIdDocument extends RequiredDocument
{
    /**
     * @var DocumentFilter|null
     */
    private $filter;

    /**
     * @param DocumentFilter|null $filter
     */
    public function __construct(?DocumentFilter $filter)
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
