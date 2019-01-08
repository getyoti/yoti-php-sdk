<?php

namespace Yoti\Util\Profile;

use Attrpubapi\AttributeList;
use Attrpubapi\Attribute as ProtobufAttribute;

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
    public static function convertToProtobufAttributeList($encryptedData, $wrappedReceiptKey, $pem)
    {
        $decryptedCipherText = self::decryptCipherText(
            $encryptedData, $wrappedReceiptKey, $pem
        );

        $attributeList = new \Attrpubapi\AttributeList();
        $attributeList->mergeFromString($decryptedCipherText);

        return $attributeList;
    }

    /**
     * Return decrypted cipher text.
     *
     * @param $encryptedData
     * @param $wrappedReceiptKey
     * @param $pem
     *
     * @return string
     */
    private static function decryptCipherText($encryptedData, $wrappedReceiptKey, $pem)
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

        return $cipherText;
    }
}