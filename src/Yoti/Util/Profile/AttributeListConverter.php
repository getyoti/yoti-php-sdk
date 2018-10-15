<?php

namespace Yoti\Util\Profile;

use Attrpubapi_v1\AttributeList;
use Attrpubapi_v1\Attribute as ProtobufAttribute;

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

        foreach($attributeList->getAttributes() as $attr) { /** @var ProtobufAttribute $attr */
            $attrName = $attr->getName();
            if (NULL === $attrName) {
                continue;
            }
            $yotiAttributes[$attr->getName()] = AttributeConverter::convertToYotiAttribute($attr);
        }
        return $yotiAttributes;
    }

    /**
     * Return Protobuf AttributeList.
     *
     * @param $encryptedData
     * @param $wrappedReceiptKey
     * @param $pem
     *
     * @return AttributeList
     */
    public static function decryptData($encryptedData, $wrappedReceiptKey, $pem)
    {
        // Unwrap key and get profile
        openssl_private_decrypt(base64_decode($wrappedReceiptKey), $unwrappedKey, $pem);

        // Decipher encrypted data with unwrapped key and IV
        $cipherText = openssl_decrypt(
            $encryptedData->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $encryptedData->getIv()
        );

        $attributeList = new \Attrpubapi_v1\AttributeList();
        $attributeList->mergeFromString($cipherText);

        return $attributeList;
    }
}