<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines a contraint for wanted attributes.
 */
class SourceConstraint implements \JsonSerializable
{
    /**
     * Constraint Type.
     */
    private const TYPE = 'SOURCE';

    /**
     * @var \Yoti\ShareUrl\Policy\WantedAnchor[]
     */
    private $anchors = [];

    /**
     * @var bool
     */
    private $softPreference;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAnchor[] $anchors
     * @param bool $softPreference
     */
    public function __construct(array $anchors, bool $softPreference = false)
    {
        Validation::isArrayOfType($anchors, [WantedAnchor::class], 'anchors');
        $this->anchors = $anchors;

        Validation::isBoolean($softPreference, 'softPreference');
        $this->softPreference = $softPreference;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => self::TYPE,
            'preferred_sources' => [
                'anchors' => $this->anchors,
                'soft_preference' => $this->softPreference,
            ],
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
