<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\Attribute;

use Yoti\Exception\AttributeException;
use Yoti\Media\Image;
use Yoti\Media\Image\Jpeg;
use Yoti\Media\Image\Png;
use Yoti\Profile\ApplicationProfile;
use Yoti\Profile\Attribute;
use Yoti\Profile\Attribute\DocumentDetails;
use Yoti\Profile\Attribute\MultiValue;
use Yoti\Profile\UserProfile;
use Yoti\Protobuf\Attrpubapi\Attribute as ProtobufAttribute;
use Yoti\Util\DateTime;
use Yoti\Util\Json;

class AttributeConverter
{
    private const CONTENT_TYPE_UNDEFINED = 0;
    private const CONTENT_TYPE_STRING = 1;
    private const CONTENT_TYPE_JPEG = 2;
    private const CONTENT_TYPE_DATE = 3;
    private const CONTENT_TYPE_PNG = 4;
    private const CONTENT_TYPE_JSON = 5;
    private const CONTENT_TYPE_MULTI_VALUE = 6;
    private const CONTENT_TYPE_INT = 7;

    /**
     * @param mixed $value
     * @param string $attrName
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\AttributeException
     */
    private static function convertValueBasedOnAttributeName($value, string $attrName)
    {
        switch ($attrName) {
            case UserProfile::ATTR_DOCUMENT_DETAILS:
                return new DocumentDetails($value);

            case UserProfile::ATTR_DOCUMENT_IMAGES:
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
     * @param string $value
     * @param int $contentType
     *
     * @return mixed
     *
     * @throws AttributeException
     */
    private static function convertValueBasedOnContentType(string $value, int $contentType)
    {
        if (strlen($value) === 0 && ($contentType !== self::CONTENT_TYPE_STRING)) {
            throw new AttributeException("Warning: Value is NULL");
        }

        switch ($contentType) {
            case self::CONTENT_TYPE_JPEG:
                return new Jpeg($value);
            case self::CONTENT_TYPE_PNG:
                return new Png($value);
            case self::CONTENT_TYPE_JSON:
                // Convert JSON string to an array
                return Json::decode($value);

            case self::CONTENT_TYPE_DATE:
                return DateTime::stringToDateTime($value);

            case self::CONTENT_TYPE_MULTI_VALUE:
                return self::convertMultiValue($value);

            case self::CONTENT_TYPE_INT:
                return (int) $value;

            case self::CONTENT_TYPE_STRING:
                return $value;

            case self::CONTENT_TYPE_UNDEFINED:
            default:
                error_log("Unknown Content Type '{$contentType}', parsing as a String", 0);
                return $value;
        }
    }

    /**
     * Convert attribute value to MultiValue.
     *
     * @param string $value
     *
     * @return MultiValue
     */
    private static function convertMultiValue($value): MultiValue
    {
        $protoMultiValue = new \Yoti\Protobuf\Attrpubapi\MultiValue();
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
     * Return a Yoti Attribute.
     *
     * @param ProtobufAttribute $protobufAttribute
     *
     * @return Attribute|null
     */
    public static function convertToYotiAttribute(ProtobufAttribute $protobufAttribute): ?Attribute
    {
        $yotiAttribute = null;

        // Application Logo can be empty, return NULL when this occurs.
        if (
            $protobufAttribute->getName() == ApplicationProfile::ATTR_APPLICATION_LOGO &&
            strlen($protobufAttribute->getValue()) === 0
        ) {
            return $yotiAttribute;
        }

        try {
            $yotiAnchors = AnchorListConverter::convert(
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
                $yotiAnchors
            );
        } catch (AttributeException $e) {
            error_log("{$e->getMessage()} (Attribute: {$protobufAttribute->getName()})", 0);
        } catch (\Exception $e) {
            error_log($e->getMessage(), 0);
        }

        return $yotiAttribute;
    }
}
