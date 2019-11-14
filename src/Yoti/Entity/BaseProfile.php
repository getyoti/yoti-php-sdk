<?php

namespace Yoti\Entity;

class BaseProfile
{
    /**
     * @var \Yoti\Entity\Attribute[]
     */
    private $attributesList;

    /**
     * @var \Yoti\Entity\Attribute[][] keyed by attribute name.
     */
    private $attributesMap;

    /**
     * @deprecated 3.0.0 replaced by $attributesMap
     *
     * @var mixed[]
     */
    protected $profileData;

    /**
     * Profile constructor.
     *
     * @param \Yoti\Entity\Attribute[] $attributesList
     */
    public function __construct(array $attributesList)
    {
        $this->setAttributesList($attributesList);
        $this->setAttributesMap();

        /** @deprecated 3.0.0 Set profileData property for backwards compatibility. */
        $this->profileData = $attributesList;
    }

    /**
     * Set attributes list.
     *
     * @param \Yoti\Entity\Attribute[] $attributesList
     */
    private function setAttributesList($attributesList)
    {
        $this->attributesList = array_filter(
            array_values($attributesList),
            function ($attribute) {
                return $attribute instanceof Attribute;
            }
        );
    }

    /**
     * Set attributes map keyed by attribute name.
     */
    private function setAttributesMap()
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
     * @param $attributeName.
     *
     * @return \Yoti\Entity\Attribute[]
     */
    public function getAttributesByName($attributeName)
    {
        if (isset($this->attributesMap[$attributeName])) {
            return $this->attributesMap[$attributeName];
        }
        return [];
    }

    /**
     * @param $attributeName.
     *
     * @return \Yoti\Entity\Attribute|null
     */
    public function getProfileAttribute($attributeName)
    {
        $attributes = $this->getAttributesByName($attributeName);
        if (!empty($attributes)) {
            return reset($attributes);
        }
        return null;
    }

    /**
     * Get attributes array keyed by name.
     *
     * @deprecated 3.0.0 replaced by ::getAttributesList()
     *
     * @return \Yoti\Entity\Attribute[]
     */
    public function getAttributes()
    {
        return array_reduce(
            $this->attributesMap,
            function ($carry, $attributes) {
                $attribute = reset($attributes);
                $carry[$attribute->getName()] = $attribute;
                return $carry;
            },
            []
        );
    }

    /**
     * Get all attributes.
     *
     * @return \Yoti\Entity\Attribute[]
     */
    public function getAttributesList()
    {
        return $this->attributesList;
    }
}
