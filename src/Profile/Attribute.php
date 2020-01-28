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
     * @var \Yoti\Profile\Attribute\Anchor[]
     */
    private $anchors;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     * @param \Yoti\Profile\Attribute\Anchor[] $anchors
     */
    public function __construct(string $name, $value, array $anchors)
    {
        $this->name = $name;
        $this->value = $value;
        $this->anchors = $anchors;
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
     * @return \Yoti\Profile\Attribute\Anchor[]
     */
    public function getSources(): array
    {
        return $this->filterAnchors(Anchor::TYPE_SOURCE_NAME);
    }

    /**
     * @return \Yoti\Profile\Attribute\Anchor[]
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
     * @return \Yoti\Profile\Attribute\Anchor[]
     */
    public function getAnchors(): array
    {
        return $this->anchors;
    }

    /**
     * @param string $type
     *
     * @return \Yoti\Profile\Attribute\Anchor[]
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
}
