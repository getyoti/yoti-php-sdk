<?php
namespace Yoti\Util\Profile;

use Yoti\Entity\Attribute;
use Yoti\Entity\DocumentDetails;
use Yoti\Entity\Image;
use Yoti\Entity\Profile;
use Yoti\Entity\ApplicationProfile;
use Compubapi_v1\EncryptedData;
use Attrpubapi_v1\Attribute as ProtobufAttribute;
use Yoti\Exception\AttributeException;

class AttributeConverter
{
    /**
     * @param ProtobufAttribute $attribute
     *
     * @return false|\Protobuf\Stream|string|DocumentDetails|Image
     *
     * @throws \Yoti\Exception\AttributeException
     */
    public static function convertValueBasedOnAttributeName(ProtobufAttribute $attribute)
    {
        $value = $attribute->getValue();
        $attrName = $attribute->getName();

        if (empty($value)) {
            throw new AttributeException("{$attrName} Attribute value is NULL");
        }

        switch($attrName)
        {
            case Profile::ATTR_DOCUMENT_DETAILS:
                return new DocumentDetails($value);
            case Profile::ATTR_STRUCTURED_POSTAL_ADDRESS:
                // Convert structured_postal_address value to an Array
                return json_encode($value, true);
            case ApplicationProfile::ATTR_APPLICATION_LOGO:
                $format = self::getImageFormat($attribute->getContentType());
                return new Image($value, $format);
            default:
                return $value;
        }
    }

    public static function getImageFormat($type)
    {
        $type = (int)$type;

        switch($type)
        {
            case 2:
                $format = 'JPEG';
                break;
            case 4:
                $format = 'PNG';
                break;
            default:
                $format = 'PNG';

        }
        return $format;
    }

    /**
     * Parses a protobuf binary contained in a string.
     *
     * @param @param string $data Binary protobuf data.
     *
     * @return EncryptedData
     */
    public static function getEncryptedData($data)
    {
        // Get cipher_text and iv
        $encryptedData = new EncryptedData();
        $encryptedData->mergeFromString(base64_decode($data));

        return $encryptedData;
    }

    /**
     * Return a Yoti Attribute.
     *
     * @param ProtobufAttribute $protobufAttribute
     *
     * @return null|Attribute
     */
    public static function convertToYotiAttribute(ProtobufAttribute $protobufAttribute)
    {
        try {
            $anchorsProcessor = new AnchorProcessor();
            $attributeAnchors = $anchorsProcessor->process(
                $protobufAttribute->getAnchors()
            );
            $attrName = $protobufAttribute->getName();
            $attrValue = AttributeConverter::convertValueBasedOnAttributeName($protobufAttribute);
            $yotiAttribute = new Attribute(
                $attrName,
                $attrValue,
                $attributeAnchors['sources'],
                $attributeAnchors['verifiers']
            );
        } catch (\Exception $e) {
            $yotiAttribute = NULL;
        }

        return $yotiAttribute;
    }
}