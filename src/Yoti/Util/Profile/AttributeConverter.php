<?php
namespace Yoti\Util\Profile;

use Yoti\Entity\Attribute;
use Yoti\Entity\DocumentDetails;
use Yoti\Entity\Image;
use Yoti\Entity\ApplicationProfile;
use Attrpubapi_v1\Attribute as ProtobufAttribute;

class AttributeConverter
{
    public static function convertValueBasedOnAttributeName(ProtobufAttribute $attribute)
    {
        $value = $attribute->getValue();
        $attrName = $attribute->getName();

        if (empty($value)) {
            return '';
        }

        switch($attrName)
        {
            case Attribute::DOCUMENT_DETAILS:
                return new DocumentDetails($value);
            case Attribute::STRUCTURED_POSTAL_ADDRESS:
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
}