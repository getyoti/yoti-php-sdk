<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Json;

/**
 * Defines the wanted anchor value and sub type.
 */
class WantedAnchor implements \JsonSerializable
{
    /**
     * Passport value.
     */
    public const VALUE_PASSPORT = 'PASSPORT';

    /**
     * Driving Licence value.
     */
    public const VALUE_DRIVING_LICENSE = 'DRIVING_LICENCE';

    /**
     * National ID value.
     */
    public const VALUE_NATIONAL_ID = 'NATIONAL_ID';

    /**
     * Passcard value.
     */
    public const VALUE_PASSCARD = 'PASS_CARD';

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $subType;

    /**
     * @param string $value
     * @param string $subType
     */
    public function __construct(string $value, string $subType = '')
    {
        $this->value = $value;
        $this->subType = $subType;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->value,
            'sub_type' => $this->subType,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
