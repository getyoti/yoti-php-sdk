<?php

declare(strict_types=1);

namespace Yoti\Profile\ExtraData;

use Yoti\Util\Json;

class AttributeDefinition implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
