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
    private $sources;

    /**
     * @var \Yoti\Profile\Attribute\Anchor[]
     */
    private $verifiers;

    /**
     * @var \Yoti\Profile\Attribute\Anchor[]
     */
    private $anchors;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param mixed $value
     * @param array<string, array> $anchorsMap
     */
    public function __construct(string $name, $value, array $anchorsMap)
    {
        $this->name = $name;
        $this->value = $value;

        $this->setSources($anchorsMap);
        $this->setVerifiers($anchorsMap);
        $this->setAnchors($anchorsMap);
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
        return $this->sources;
    }

    /**
     * @return \Yoti\Profile\Attribute\Anchor[]
     */
    public function getVerifiers(): array
    {
        return $this->verifiers;
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
     * @param array<string, array> $anchorsMap
     */
    private function setSources(array $anchorsMap): void
    {
        $this->sources = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_SOURCE_OID
        );
    }

    /**
     * @param array<string, array> $anchorsMap
     */
    private function setVerifiers(array $anchorsMap): void
    {
        $this->verifiers = $this->getAnchorType(
            $anchorsMap,
            Anchor::TYPE_VERIFIER_OID
        );
    }

    /**
     * @param array<string, array> $anchorsMap
     */
    private function setAnchors(array $anchorsMap): void
    {
        // Remove Oids from the anchorsMap
        $anchors = [];
        array_walk($anchorsMap, function ($val) use (&$anchors): void {
            $anchors = array_merge($anchors, array_values($val));
        });
        $this->anchors = $anchors;
    }

    /**
     * @param array<string, array> $anchorsMap
     * @param string $anchorType
     *
     * @return \Yoti\Profile\Attribute\Anchor[]
     */
    private function getAnchorType(array $anchorsMap, string $anchorType): array
    {
        return isset($anchorsMap[$anchorType]) ? $anchorsMap[$anchorType] : [];
    }
}
