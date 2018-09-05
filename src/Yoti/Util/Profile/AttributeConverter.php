<?php
namespace Yoti\Util\Profile;


use Yoti\Entity\Attribute;
use Yoti\Entity\DocumentDetails;

class AttributeConverter
{
    public static function convertValueBasedOnAttributeName($value, $attrName)
    {
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
            default:
                return $value;
        }
    }
}