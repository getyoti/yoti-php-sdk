<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

abstract class DocumentFilter implements \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object) [
            'type' => $this->type,
        ];
    }
}
