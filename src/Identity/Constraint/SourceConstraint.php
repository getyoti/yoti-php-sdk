<?php

namespace Yoti\Identity\Constraint;

use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Util\Validation;

class SourceConstraint implements \JsonSerializable, Constraint
{
    private string $type;

    private PreferredSources $preferredSources;

    /**
     * @param WantedAnchor[] $wantedAnchors
     * @param bool $softPreference
     */
    public function __construct(array $wantedAnchors, bool $softPreference)
    {
        $this->type = 'SOURCE';

        Validation::isArrayOfType($wantedAnchors, [WantedAnchor::class], 'anchors');
        $this->preferredSources = new PreferredSources($wantedAnchors, $softPreference);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPreferredSources(): PreferredSources
    {
        return $this->preferredSources;
    }

    public function jsonSerialize(): object
    {
        return (object)[
            'type' => $this->getType(),
            'preferred_sources' => $this->getPreferredSources(),
        ];
    }
}
