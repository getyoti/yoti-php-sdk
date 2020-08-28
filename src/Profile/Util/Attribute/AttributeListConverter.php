<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Psr\Log\LoggerInterface;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\Logger;

class AttributeListConverter
{
    /**
     * @var AttributeConverter
     */
    private $attributeConverter;

    public function __construct(LoggerInterface $logger)
    {
        $this->attributeConverter = new AttributeConverter($logger);
    }

    /**
     * Convert Protobuf AttributeList to array of Yoti Attributes.
     *
     * @param \Yoti\Protobuf\Attrpubapi\AttributeList $attributeList
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public function convert(AttributeList $attributeList): array
    {
        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var \Yoti\Protobuf\Attrpubapi\Attribute $attr */
            $attrName = $attr->getName();
            if (null === $attrName || strlen($attrName) === 0) {
                continue;
            }
            $yotiAttribute = $this->attributeConverter->convert($attr);
            if ($yotiAttribute !== null) {
                $yotiAttributes[] = $yotiAttribute;
            }
        }
        return $yotiAttributes;
    }

    /**
     * Convert Protobuf AttributeList to array of Yoti Attributes.
     *
     * @deprecated replaced by AttributeListConverter::convert()
     *
     * @param \Yoti\Protobuf\Attrpubapi\AttributeList $attributeList
     *
     * @return \Yoti\Profile\Attribute[]
     */
    public static function convertToYotiAttributesList(AttributeList $attributeList): array
    {
        return (new self(new Logger()))->convert($attributeList);
    }
}
