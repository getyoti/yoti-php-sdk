<?php

namespace Yoti\Identity\Policy;

use Yoti\Identity\Constraint\Constraint;

class WantedAttributeBuilder
{
    private string $name;

    private ?string $derivation = null;

    private bool $optional = false;

    /**
     * @var Constraint[]
     */
    private ?array $constraints = null;

    private ?bool $acceptSelfAsserted = null;

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withDerivation(string $derivation): self
    {
        $this->derivation = $derivation;

        return $this;
    }

    public function withOptional(bool $optional): self
    {
        $this->optional = $optional;

        return $this;
    }

    public function withAcceptSelfAsserted(bool $acceptSelfAsserted): self
    {
        $this->acceptSelfAsserted = $acceptSelfAsserted;

        return $this;
    }

    /**
     * @param Constraint[] $constraints
     */
    public function withConstraints(array $constraints): self
    {
        $this->constraints = $constraints;

        return $this;
    }

    public function withConstraint(Constraint $constraint): self
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    public function build(): WantedAttribute
    {
        return new WantedAttribute(
            $this->name,
            $this->derivation,
            $this->optional,
            $this->acceptSelfAsserted,
            $this->constraints,
        );
    }
}
