<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Psr\Log\LoggerInterface;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\Logger;

class AttributeListConverter
{
    /**
     * Convert Protobuf AttributeList to array of Yoti Attributes.
     *
     * @param \Yoti\Protobuf\Attrpubapi\AttributeList $attributeList
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public static function convertToYotiAttributesList(
        AttributeList $attributeList,
        ?LoggerInterface $logger = null
    ): array {
        $logger = $logger ?? new Logger();

        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var \Yoti\Protobuf\Attrpubapi\Attribute $attr */
            $attrName = $attr->getName();
            if (null === $attrName || strlen($attrName) === 0) {
                continue;
            }
            $yotiAttribute = AttributeConverter::convertToYotiAttribute($attr, $logger);
            if ($yotiAttribute !== null) {
                $yotiAttributes[] = $yotiAttribute;
            }
        }
        return $yotiAttributes;
    }
}
