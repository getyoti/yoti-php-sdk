<?php

declare(strict_types=1);

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
     * @param \Yoti\ShareUrl\Policy\SourceConstraint $sourceConstraint
     *
     * @return $this
     */
    public function withSourceConstraint(SourceConstraint $sourceConstraint): self
    {
        $this->constraints[] = $sourceConstraint;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\Constraints
     */
    public function build(): Constraints
    {
        return new Constraints($this->constraints);
    }
}
