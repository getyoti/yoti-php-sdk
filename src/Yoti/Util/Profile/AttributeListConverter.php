<?php

namespace Yoti\Util\Profile;

use Attrpubapi\AttributeList;
use Attrpubapi\Attribute as ProtobufAttribute;
use Yoti\Util\EncryptedData;

class AttributeListConverter
{
    /**
     * Convert Protobuf AttributeList to Yoti Attributes map.
     *
     * @param AttributeList $attributeList
     *
     * @return array
     */
    public static function convertToYotiAttributesMap(AttributeList $attributeList)
    {
        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var ProtobufAttribute $attr */
            $attrName = $attr->getName();
            if (null === $attrName) {
                continue;
            }
            $yotiAttributes[$attr->getName()] = AttributeConverter::convertToYotiAttribute($attr);
        }
        return $yotiAttributes;
    }

    /**
     * Return Protobuf AttributeList.
     *
     * @deprecated 3.0.0 No longer in use.
     *
     * @param $encryptedData
     * @param $wrappedReceiptKey
     * @param $pem
     *
     * @return AttributeList
     */
    public static function convertToProtobufAttributeList($encryptedData, $wrappedReceiptKey, $pem)
    {
        $attributeList = new \Attrpubapi\AttributeList();
        $attributeList->mergeFromString(
            EncryptedData::decryptFromProto($encryptedData, $wrappedReceiptKey, $pem)
        );

        return $attributeList;
    }
}
