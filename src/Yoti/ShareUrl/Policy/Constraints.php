<?php

namespace Yoti\ShareUrl\Policy;

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

    /**
     * Return JSON string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * Ensure that all constraints are an allowed type.
     *
     * @param \JsonSerializable[]
     *
     * @throws \TypeError
     */
    private function validateConstraints(array $constraints)
    {
        foreach ($constraints as $constraint) {
            $this->validateConstraint($constraint);
        }
    }

    /**
     * Ensure that constraint is an allowed type.
     *
     * @param $constraint
     *
     * @throws \TypeError
     */
    private function validateConstraint($constraint)
    {
        $allowedTypes = [
            SourceConstraint::class,
        ];

        foreach ($allowedTypes as $allowedType) {
            if ($constraint instanceof $allowedType) {
                return;
            }
        }

        throw new \TypeError('Constraints must be instance of ' . implode(', ', $allowedTypes));
    }
}
