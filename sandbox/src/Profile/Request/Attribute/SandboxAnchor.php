<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

class SandboxAnchor implements \JsonSerializable
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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
            'sub_type' => $this->subtype,
            'timestamp' => $this->timestamp * 1000000,
        ];
    }
}
