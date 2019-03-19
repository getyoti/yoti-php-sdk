<?php
namespace Yoti\Util\Profile;

use Yoti\Entity\Attribute;
use Yoti\Entity\DocumentDetails;
use Yoti\Entity\Image;
use Yoti\Entity\Profile;
use Compubapi\EncryptedData;
use Attrpubapi\Attribute as ProtobufAttribute;
use Yoti\Exception\AttributeException;
use Yoti\Entity\MultiValue;

class AttributeConverter
{
    const CONTENT_TYPE_UNDEFINED = 0;
    const CONTENT_TYPE_STRING = 1;
    const CONTENT_TYPE_JPEG = 2;
    const CONTENT_TYPE_DATE = 3;
    const CONTENT_TYPE_PNG = 4;
    const CONTENT_TYPE_JSON = 5;
    const CONTENT_TYPE_MULTI_VALUE = 6;
    const CONTENT_TYPE_INT = 7;

    /**
     * @param ProtobufAttribute $attribute
     *
     * @return false|\Protobuf\Stream|string|DocumentDetails|Image
     *
     * @throws \Yoti\Exception\AttributeException
     */
    private static function convertValueBasedOnAttributeName($value, $attrName)
    {
        switch ($attrName) {
            case Profile::ATTR_DOCUMENT_DETAILS:
                return new DocumentDetails($value);

            case Profile::ATTR_DOCUMENT_IMAGES:
                if (!($value instanceof MultiValue)) {
                    throw new AttributeException('Document Images could not be decoded');
                }
                return $value
                  ->allowInstance(Image::class)
                  ->immutable();

            default:
                return $value;
        }
    }

    /**
     * @param $value
     * @param $contentType
     *
     * @return \DateTime|Image
     *
     * @throws AttributeException
     */
    private static function convertValueBasedOnContentType($value, $contentType)
    {
        if (strlen($value) === 0 && ($contentType !== self::CONTENT_TYPE_STRING)) {
            throw new AttributeException("Warning: Value is NULL");
        }

        switch ($contentType) {
            case self::CONTENT_TYPE_JPEG:
            case self::CONTENT_TYPE_PNG:
                $imageExtension = self::imageTypeToExtension($contentType);
                return new Image($value, $imageExtension);

            case self::CONTENT_TYPE_JSON:
                // Convert JSON string to an array
                $value = json_decode($value, true);
                if (json_last_error()) {
                    throw new AttributeException("Error converting attr to a JSON Object");
                }
                return $value;

            case self::CONTENT_TYPE_DATE:
                return self::convertTimestampToDate($value);

            case self::CONTENT_TYPE_MULTI_VALUE:
                return self::convertMultiValue($value);

            case self::CONTENT_TYPE_UNDEFINED:
                throw new AttributeException("Content Type is undefined");

            case self::CONTENT_TYPE_INT:
                return (int) $value;

            case self::CONTENT_TYPE_STRING:
            default:
                return $value;
        }
    }

    /**
     * Convert attribute value to MultiValue.
     *
     * @param string $value
     * @return MultiValue
     */
    private static function convertMultiValue($value)
    {
        $protoMultiValue = new \Attrpubapi\MultiValue();
        $protoMultiValue->mergeFromString($value);
        $items = [];
        foreach ($protoMultiValue->getValues() as $protoValue) {
            $items[] = self::convertValueBasedOnContentType(
                $protoValue->getData(),
                $protoValue->getContentType()
            );
        }
        return new MultiValue($items);
    }

    /**
     * Convert Protobuf Image type to an image extension.
     *
     * @param int $type
     *
     * @return string
     */
    private static function imageTypeToExtension($type)
    {
        $type = (int)$type;

        switch ($type) {
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
        $yotiAttribute = null;

        try {
            $yotiAnchorsMap = AnchorListConverter::convert(
                $protobufAttribute->getAnchors()
            );
            $attrValue = AttributeConverter::convertValueBasedOnContentType(
                $protobufAttribute->getValue(),
                $protobufAttribute->getContentType()
            );
            $attrName = $protobufAttribute->getName();
            $attrValue = AttributeConverter::convertValueBasedOnAttributeName(
                $attrValue,
                $attrName
            );
            $yotiAttribute = new Attribute(
                $attrName,
                $attrValue,
                $yotiAnchorsMap
            );
        } catch (AttributeException $e) {
            error_log($e->getMessage() . " (Attribute: {$protobufAttribute->getName()})", 0);
        } catch (\Exception $e) {
            error_log($e->getMessage(), 0);
        }

        return $yotiAttribute;
    }

    /**
     * @param $value
     *
     * @return \DateTime
     */
    public static function convertTimestampToDate($value)
    {
        return (new \DateTime())->setTimestamp(strtotime($value));
    }
}
