<?php

namespace Yoti\ShareUrl\Policy;

/**
 * Builder for Constraints.
 */
class ConstraintsBuilder
{
    /**
     * @var mixed[]
     */
    private $constraints = [];

    /**
     * @param \Yoti\ShareUrl\Policy\SourceConstraint $constraint
     */
    public function withSourceConstraint(SourceConstraint $sourceConstraint)
    {
        $this->constraints[] = $sourceConstraint;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\Constraints
     */
    public function build()
    {
        return new Constraints($this->constraints);
    }
}
