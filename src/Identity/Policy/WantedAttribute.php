<?php

namespace Yoti\Identity\Policy;

use stdClass;
use Yoti\Identity\Constraint\Constraint;
use Yoti\Util\Validation;

/**
 * Defines the wanted attribute name and derivation.
 */
class WantedAttribute implements \JsonSerializable
{
    private string $name;

    private ?string $derivation;

    private bool $optional;

    /**
     * @var Constraint[]
     */
    private ?array $constraints = null;

    private ?bool $acceptSelfAsserted;

    /**
     * @param Constraint[] $constraints
     */
    public function __construct(
        string $name,
        string $derivation = null,
        bool $optional = false,
        bool $acceptSelfAsserted = null,
        array $constraints = null
    ) {
        Validation::notEmptyString($name, 'name');
        $this->name = $name;

        $this->derivation = $derivation;
        $this->optional = $optional;
        $this->acceptSelfAsserted = $acceptSelfAsserted;

        if (null !== $constraints) {
            Validation::isArrayOfType($constraints, [Constraint::class], 'constraints');
            $this->constraints = $constraints;
        }
    }

    /**
     * Name identifying the WantedAttribute
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Additional derived criteria.
     *
     * @return string
     */
    public function getDerivation(): ?string
    {
        return $this->derivation;
    }

    /**
     * List of constraints to add to an attribute.
     *
     * If you do not provide any particular constraints, Yoti will provide you with the
     * information from the most recently added source.
     *
     * @return  Constraint[] $constraints
     */
    public function getConstraints(): ?array
    {
        return $this->constraints;
    }

    /**
     * Accept self asserted attributes.
     *
     * These are attributes that have been self-declared, and not verified by Yoti.
     *
     * @return bool|null
     */
    public function getAcceptSelfAsserted(): ?bool
    {
        return $this->acceptSelfAsserted;
    }

    /**
     * @return bool
     */
    public function getOptional(): bool
    {
        return $this->optional;
    }

    public function jsonSerialize(): stdClass
    {
        $data = new stdClass();
        $data->name = $this->getName();
        $data->optional = $this->getOptional();

        if (null !== $this->getDerivation()) {
            $data->derivation = $this->getDerivation();
        }

        if (null !== $this->getConstraints()) {
            $data->constraints = $this->getConstraints();
        }

        if (null !== $this->getAcceptSelfAsserted()) {
            $data->accept_self_asserted = $this->getAcceptSelfAsserted();
        }

        return $data;
    }
}
