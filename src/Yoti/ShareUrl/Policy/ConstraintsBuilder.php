<?php

namespace Yoti\ShareUrl\Policy;

/**
 * Builder for Constraints.
 *
 * @class ConstraintsBuilder
 */
class ConstraintsBuilder
{
    /**
     * @var Constraints
     */
    private $constraints = [];

    /**
     * @param SourceConstraint $sourceConstraint
     */
    public function withSourceConstraint(SourceContraint $sourceConstraint)
    {
        $this->constraints[] = $sourceConstraint;
        return $this;
    }

    /**
     * @return Constraints
     */
    public function build()
    {
        return new Constraints($this->constraints);
    }
}
