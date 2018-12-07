<?php

namespace YotiSandbox\Entity;

class SandboxAnchor
{
    private $type;
    private $value;
    private $subtype;
    private $timestamp;

    public function __construct($type, $value, $subType, $timestamp)
    {
        $this->type = $type;
        $this->value = $value;
        $this->subtype = $subType;
        $this->timestamp = $timestamp;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSubtype()
    {
        return $this->subtype;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
}