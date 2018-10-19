<?php
namespace Yoti\Util\Profile;

use Yoti\Entity\Attribute;
use Yoti\Entity\DocumentDetails;
use Yoti\Entity\Image;
use Yoti\Entity\Profile;
use Compubapi_v1\EncryptedData;
use Attrpubapi_v1\Attribute as ProtobufAttribute;
use Yoti\Exception\AttributeException;

class AttributeConverter
{
    const CONTENT_TYPE_PNG = 4;
    const CONTENT_TYPE_JPEG = 2;
    const CONTENT_TYPE_DATE = 3;

    /**
     * @param ProtobufAttribute $attribute
     *
     * @return false|\Protobuf\Stream|string|DocumentDetails|Image
     *
     * @throws \Yoti\Exception\AttributeException
     */
    public static function convertValueBasedOnAttributeName($value, $attrName)
    {
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

            default:
                return $value;
        }
    }

    public static function convertValueBasedOnContentType($value, $contentType)
    {
        if (empty($value)) {
            throw new AttributeException("Attribute value is NULL");
        }

        switch($contentType)
        {
            case self::CONTENT_TYPE_JPEG:
            case self::CONTENT_TYPE_PNG:
                $imageExtension = self::imageTypeToExtension($contentType);
                $value = new Image($value, $imageExtension);
                break;

            case self::CONTENT_TYPE_DATE:
                $dateTime = new \DateTime();
                $dateTime->setTimestamp(strtotime($value));
                $value = $dateTime;
                break;
        }

        return $value;
    }

    /**
     * Convert Protobuf Image type to an image extension.
     *
     * @param int $type
     *
     * @return string
     */
    public static function imageTypeToExtension($type)
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
                $format = 'UNSUPPORTED';

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
            $attrValue = AttributeConverter::convertValueBasedOnContentType(
                $protobufAttribute->getValue(),
                $protobufAttribute->getContentType()
            );
            $attrValue = AttributeConverter::convertValueBasedOnAttributeName(
                $attrValue,
                $attrName
            );
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