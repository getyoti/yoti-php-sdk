<?php

declare(strict_types=1);

namespace YotiSandbox\Entity;

use Yoti\Util\Validation;

class SandboxAttribute
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    /** @var string */
    protected $derivation;

    /** @var string */
    protected $optional;

    /** @var \YotiSandbox\Entity\SandboxAnchor[] */
    protected $anchors;

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     */
    public function __construct(
        string $name,
        string $value,
        string $derivation = '',
        string $optional = 'false',
        array $anchors = []
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->derivation = $derivation;
        $this->optional = $optional;

        Validation::isArrayOfType($anchors, [\YotiSandbox\Entity\SandboxAnchor::class], 'anchors');
        $this->anchors = $anchors;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDerivation(): string
    {
        return $this->derivation;
    }

    public function getOptional(): string
    {
        return $this->optional;
    }

    /**
     * @return \YotiSandbox\Entity\SandboxAnchor[]
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }
}
