<?php

namespace Yoti\Identity\Policy;

use stdClass;

/**
 * Defines the wanted anchor value and subtype.
 */
class WantedAnchor implements \JsonSerializable
{
    private string $value;

    private string $subType;

    public function __construct(string $value, string $subType = '')
    {
        $this->value = $value;
        $this->subType = $subType;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)[
            'name' => $this->value,
            'sub_type' => $this->subType,
        ];
    }
}
