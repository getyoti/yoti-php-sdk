<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines the wanted anchor value and sub type.
 */
class WantedAnchor implements \JsonSerializable
{
    const VALUE_PASSPORT = 'PASSPORT';
    const VALUE_DRIVING_LICENSE = 'DRIVING_LICENCE';
    const VALUE_NATIONAL_ID = 'NATIONAL_ID';
    const VALUE_PASSCARD = 'PASS_CARD';

    /**
     * @param string $value
     * @param string $subType
     */
    public function __construct($value, $subType = '')
    {
        Validation::isString($value, 'value');
        $this->value = $value;

        Validation::isString($subType, 'subType');
        $this->subType = $subType;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->value,
            'sub_type' => $this->subType,
        ];
    }
}
