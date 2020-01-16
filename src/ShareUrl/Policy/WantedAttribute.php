<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines the wanted attribute name and derivation.
 */
class WantedAttribute implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $derivation;

    /**
     * @var \Yoti\ShareUrl\Policy\Constraints|null
     */
    private $constraints;

    /**
     * @var bool|null
     */
    private $acceptSelfAsserted;

    /**
     * @param string $name
     * @param string $derivation
     * @param bool $acceptSelfAsserted
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     */
    public function __construct(
        string $name,
        string $derivation = null,
        bool $acceptSelfAsserted = null,
        Constraints $constraints = null
    ) {
        Validation::notEmptyString($name, 'name');
        $this->name = $name;

        $this->derivation = $derivation;
        $this->acceptSelfAsserted = $acceptSelfAsserted;
        $this->constraints = $constraints;
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
     * @return \Yoti\ShareUrl\Policy\Constraints|null
     */
    public function getConstraints(): ?Constraints
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
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $json = [
            'name' => $this->getName(),
            'optional' => false,
        ];

        if ($this->getDerivation() !== null) {
            $json['derivation'] = $this->getDerivation();
        }

        if ($this->getConstraints() !== null) {
            $json['constraints'] = $this->getConstraints();
        }

        if ($this->getAcceptSelfAsserted() !== null) {
            $json['accept_self_asserted'] = $this->getAcceptSelfAsserted();
        }

        return $json;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
