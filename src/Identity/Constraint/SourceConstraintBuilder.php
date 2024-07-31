<?php

namespace Yoti\Identity\Constraint;

use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Util\Validation;

class SourceConstraintBuilder
{
    /**
     * @var WantedAnchor[]
     */
    private array $wantedAnchors = [];

    private bool $softPreference = false;

    /**
     * @param WantedAnchor[] $wantedAnchors
     */
    public function withWantedAnchors(array $wantedAnchors): self
    {
        Validation::isArrayOfType($wantedAnchors, [WantedAnchor::class], 'anchors');
        $this->wantedAnchors = $wantedAnchors;

        return $this;
    }

    public function withWantedAnchor(WantedAnchor $wantedAnchor): self
    {
        $this->wantedAnchors[] = $wantedAnchor;

        return $this;
    }

    public function withSoftPreference(bool $softPreference): self
    {
        $this->softPreference = $softPreference;

        return $this;
    }

    public function build(): SourceConstraint
    {
        return new SourceConstraint($this->wantedAnchors, $this->softPreference);
    }
}
