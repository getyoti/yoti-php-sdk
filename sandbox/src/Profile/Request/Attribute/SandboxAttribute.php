<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

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

    /** @var \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] */
    protected $anchors;

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     * @param string $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
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

        Validation::isArrayOfType($anchors, [\Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor::class], 'anchors');
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
     * @return \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[]
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }
}
