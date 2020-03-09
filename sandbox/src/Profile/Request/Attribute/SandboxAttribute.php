<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request\Attribute;

use Yoti\Util\Validation;

class SandboxAttribute implements \JsonSerializable
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    /** @var string */
    protected $derivation;

    /** @var bool */
    protected $optional;

    /** @var \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] */
    protected $anchors;

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     */
    public function __construct(
        string $name,
        string $value,
        string $derivation = '',
        bool $optional = false,
        array $anchors = []
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->derivation = $derivation;
        $this->optional = $optional;

        Validation::isArrayOfType($anchors, [\Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor::class], 'anchors');
        $this->anchors = $anchors;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'derivation' => $this->derivation,
            'optional' => $this->optional,
            'anchors' => $this->anchors,
        ];
    }
}
