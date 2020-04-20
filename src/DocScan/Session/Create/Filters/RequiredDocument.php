<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use Yoti\Util\Json;

abstract class RequiredDocument implements \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var RequiredDocumentFilter|null
     */
    private $filter;

    /**
     * @param string $type
     * @param RequiredDocumentFilter|null $filter
     */
    public function __construct(string $type, ?RequiredDocumentFilter $filter)
    {
        $this->type = $type;
        $this->filter = $filter;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) Json::withoutNullValues([
            'type' => $this->type,
            'filter' => $this->filter,
        ]);
    }
}
