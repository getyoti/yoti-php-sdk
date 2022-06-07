<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\Attribute\Anchor;

class Attribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var Anchor[]
     */
    private $anchors;

    /**
     * @var string|null
     */
    private $id;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     * @param Anchor[] $anchors
     * @param string|null $id
     */
    public function __construct(string $name, $value, array $anchors, string $id = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->anchors = $anchors;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Anchor[]
     */
    public function getSources(): array
    {
        return $this->filterAnchors(Anchor::TYPE_SOURCE_NAME);
    }

    /**
     * @return Anchor[]
     */
    public function getVerifiers(): array
    {
        return $this->filterAnchors(Anchor::TYPE_VERIFIER_NAME);
    }

    /**
     * Return an array of anchors e.g
     * [
     *  new Anchor(),
     *  new Anchor(),
     *  ...
     * ]
     *
     * @return Anchor[]
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }

    /**
     * @param string $type
     *
     * @return Anchor[]
     */
    private function filterAnchors(string $type): array
    {
        $filteredAnchors = array_filter(
            $this->anchors,
            function (Anchor $anchor) use ($type): bool {
                return $anchor->getType() === $type;
            }
        );
        return array_values($filteredAnchors);
    }

    /**
     * Gets the ID of the attribute
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
