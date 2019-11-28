<?php

namespace Yoti\Util\Profile;

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
    public static function convertToYotiAttributesMap(AttributeList $attributeList)
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
        $decryptedCipherText = self::decryptCipherText(
            $encryptedData,
            $wrappedReceiptKey,
            $pem
        );

        $attributeList = new AttributeList();
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
        return openssl_decrypt(
            $encryptedData->getCipherText(),
            'aes-256-cbc',
            $unwrappedKey,
            OPENSSL_RAW_DATA,
            $encryptedData->getIv()
        );
    }
}
