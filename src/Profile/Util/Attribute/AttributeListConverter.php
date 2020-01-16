<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Yoti\Protobuf\Attrpubapi\AttributeList;

class AttributeListConverter
{
    /**
     * Convert Protobuf AttributeList to Yoti Attributes map.
     *
     * @param AttributeList $attributeList
     *
     * @return array
     */
    public static function convertToYotiAttributesMap(AttributeList $attributeList): array
    {
        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var Yoti\Protobuf\Attrpubapi\Attribute $attr */
            $attrName = $attr->getName();
            if (null === $attrName) {
                continue;
            }
            $yotiAttributes[$attr->getName()] = AttributeConverter::convertToYotiAttribute($attr);
        }
        return $yotiAttributes;
    }
}
