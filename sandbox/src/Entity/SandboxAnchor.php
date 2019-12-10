<?php

namespace YotiSandbox\Entity;

class SandboxAnchor
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $subtype;

    /**
     * @var int
     */
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
