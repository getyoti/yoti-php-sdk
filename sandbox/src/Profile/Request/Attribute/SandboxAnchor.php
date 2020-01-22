<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

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

    public function __construct(string $type, string $value, string $subType, int $timestamp)
    {
        $this->type = $type;
        $this->value = $value;
        $this->subtype = $subType;
        $this->timestamp = $timestamp;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
