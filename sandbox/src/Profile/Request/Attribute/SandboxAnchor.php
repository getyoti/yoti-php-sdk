<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

use Yoti\Util\DateTime;

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
     * @var \DateTime
     */
    private $timestamp;

    public function __construct(string $type, string $value, string $subType, \DateTime $timestamp)
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
            'timestamp' => DateTime::dateTimeToTimestamp($this->timestamp),
        ];
    }
}
