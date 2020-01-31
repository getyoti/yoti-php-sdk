<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Yoti\Protobuf\Attrpubapi\AttributeList;

class AttributeListConverter
{
    /**
     * Convert Protobuf AttributeList to array of Yoti Attributes.
     *
     * @param \Yoti\Protobuf\Attrpubapi\AttributeList $attributeList
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public static function convertToYotiAttributesList(AttributeList $attributeList): array
    {
        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var \Yoti\Protobuf\Attrpubapi\Attribute $attr */
            $attrName = $attr->getName();
            if (null === $attrName) {
                continue;
            }
            $yotiAttribute = AttributeConverter::convertToYotiAttribute($attr);
            if ($yotiAttribute !== null) {
                $yotiAttributes[] = $yotiAttribute;
            }
        }
        return $yotiAttributes;
    }
}
