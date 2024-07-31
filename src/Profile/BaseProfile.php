<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Util\Validation;

class BaseProfile
{
    /**
     * @var Attribute[]
     */
    private $attributesList;

    /**
     * @var Attribute[][] keyed by attribute name.
     */
    private $attributesMap;

    /**
     * Profile constructor.
     *
     * @param Attribute[] $attributesList
     */
    public function __construct(array $attributesList)
    {
        Validation::isArrayOfType($attributesList, [Attribute::class], 'attributesList');
        $this->attributesList = $attributesList;
        $this->setAttributesMap();
    }

    /**
     * Set attributes map keyed by attribute name.
     */
    private function setAttributesMap(): void
    {
        $this->attributesMap = array_reduce(
            $this->getAttributesList(),
            function ($carry, Attribute $attr) {
                $carry[$attr->getName()][] = $attr;
                return $carry;
            },
            []
        );
    }

    /**
     * @param string $attributeName.
     *
     * @return Attribute[]
     */
    public function getAttributesByName(string $attributeName): array
    {
        return $this->attributesMap[$attributeName] ?? [];
    }

    /**
     * Retrieves an attribute which matches the ID specified.
     *
     * @param string $attributeId
     *
     * @return Attribute
     */
    public function getAttributeById(string $attributeId): ?Attribute
    {
        foreach ($this->attributesList as $attribute) {
            if ($attribute->getId() == $attributeId) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param string $attributeName.
     *
     * @return Attribute|null
     */
    public function getProfileAttribute(string $attributeName): ?Attribute
    {
        $attributes = $this->getAttributesByName($attributeName);
        return count($attributes) > 0 ? $attributes[0] : null;
    }

    /**
     * Get all attributes.
     *
     * @return Attribute[]
     */
    public function getAttributesList(): array
    {
        return $this->attributesList;
    }
}
