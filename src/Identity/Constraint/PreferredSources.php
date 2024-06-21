<?php

namespace Yoti\Identity\Constraint;

use stdClass;
use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Util\Validation;

class PreferredSources implements \JsonSerializable
{
    /**
     * @var WantedAnchor[]
     */
    private array $wantedAnchors;

    private bool $softPreference;

    /**
     * @param WantedAnchor[] $wantedAnchors
     * @param bool $softPreference
     */
    public function __construct(array $wantedAnchors, bool $softPreference)
    {
        Validation::isArrayOfType($wantedAnchors, [WantedAnchor::class], 'anchors');
        $this->wantedAnchors = $wantedAnchors;

        Validation::isBoolean($softPreference, 'soft_preference');
        $this->softPreference = $softPreference;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)[
            'anchors' => $this->wantedAnchors,
            'soft_preference' => $this->softPreference,
        ];
    }

    /**
     * @return WantedAnchor[]
     */
    public function getWantedAnchors(): array
    {
        return $this->wantedAnchors;
    }

    /**
     * @return bool
     */
    public function isSoftPreference(): bool
    {
        return $this->softPreference;
    }
}
