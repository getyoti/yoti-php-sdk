<?php
namespace YotiSandbox\Entity;

class SandboxAttribute
{
    private $name;
    private $value;
    private $derivation;
    private $optional;
    private $anchors;

    public function __construct($name, $value, $derivation = '', $optional = 'false', array $anchors = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->derivation = $derivation;
        $this->optional = $optional;
        $this->anchors = $anchors;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDerivation()
    {
        return $this->derivation;
    }

    public function getOptional()
    {
        return $this->optional;
    }

    public function getAnchors()
    {
        return $this->anchors;
    }
}