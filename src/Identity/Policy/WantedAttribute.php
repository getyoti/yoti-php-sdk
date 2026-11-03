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
     * @var string[]
     */
    private array $alternativeNames = [];

    /**
     * @param Constraint[] $constraints
     * @param string[] $alternativeNames
     */
    public function __construct(
        string $name,
        ?string $derivation = null,
        bool $optional = false,
        ?bool $acceptSelfAsserted = null,
        ?array $constraints = null,
        array $alternativeNames = []
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

        if ($alternativeNames !== []) {
            Validation::isArrayOfStrings($alternativeNames, 'alternative_names');
            $this->alternativeNames = $alternativeNames;
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
     * @return string|null
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
     * @return Constraint[]|null
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

    /**
     * Alternative attribute names that can be used as a fallback.
     *
     * @return string[]
     */
    public function getAlternativeNames(): array
    {
        return $this->alternativeNames;
    }

    public function jsonSerialize(): stdClass
    {
        $data = new stdClass();
        $data->name = $this->getName();
        $data->optional = $this->getOptional();

        if (null !== $this->getDerivation()) {
            $data->derivation = $this->getDerivation();
        }

        if ($this->alternativeNames !== []) {
            $data->alternative_names = $this->alternativeNames;
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
