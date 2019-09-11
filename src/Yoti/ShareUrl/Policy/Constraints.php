<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * List of constraints to apply to a wanted attribute.
 */
class Constraints implements \JsonSerializable
{
    /**
     * @var \JsonSerializable[]
     */
    private $constraints = [];

    /**
     * @param \JsonSerializable[] $constraints
     */
    public function __construct(array $constraints = [])
    {
        Validation::isArrayOfType($constraints, [SourceConstraint::class], 'constraints');
        $this->validateConstraints($constraints);
        $this->constraints = $constraints;
    }

    /**
     * @inheritDoc
     *
     * @return \JsonSerializable[]
     */
    public function jsonSerialize()
    {
        return $this->constraints;
    }
}
