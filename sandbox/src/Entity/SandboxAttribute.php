<?php

declare(strict_types=1);

namespace YotiSandbox\Entity;

class SandboxAttribute
{
    protected $name;
    protected $value;
    protected $derivation;
    protected $optional;
    protected $anchors;

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

    public function getAnchors(): array
    {
        return $this->anchors;
    }
}
