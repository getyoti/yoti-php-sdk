<?php

namespace Yoti\Util\Profile;

use Attrpubapi\AttributeList;
use Yoti\Entity\Attribute;

class AttributeListConverter
{
    /**
     * Convert Protobuf AttributeList to array of Yoti Attributes.
     *
     * @param AttributeList $attributeList
     *
     * @return \Yoti\Entity\Attribute[]
     */
    public static function convertToYotiAttributesList(AttributeList $attributeList)
    {
        $yotiAttributes = [];

        foreach ($attributeList->getAttributes() as $attr) { /** @var \Attrpubapi\Attribute $attr */
            if ($attr->getName() === null) {
                continue;
            }
            $yotiAttribute = AttributeConverter::convertToYotiAttribute($attr);
            if (!($yotiAttribute instanceof Attribute)) {
                continue;
            }
            $yotiAttributes[] = $yotiAttribute;
        }
        return $yotiAttributes;
    }

    /**
     * Convert Protobuf AttributeList to Yoti Attributes map.
     *
     * @deprecated 3.0.0 Replaced by ::convertToYotiAttributesList
     *
     * @param AttributeList $attributeList
     *
     * @return \Yoti\Entity\Attribute[]
     */
    public static function convertToYotiAttributesMap(AttributeList $attributeList)
    {
        return array_reduce(
            self::convertToYotiAttributesList($attributeList),
            function ($carry, Attribute $attr) {
                $carry[$attr->getName()] = $attr;
                return $carry;
            },
            []
        );
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
            $encryptedData,
            $wrappedReceiptKey,
            $pem
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
