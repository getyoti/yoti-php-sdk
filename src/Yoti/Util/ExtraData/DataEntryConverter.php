<?php

namespace Yoti\Util\ExtraData;

use Yoti\Sharepubapi\DataEntry\Type as DataEntryTypeProto;

class DataEntryConverter
{
    /**
     * @param string $type
     * @param string $value
     *
     * @return \Yoti\Entity\AttributeIssuanceDetails|null
     */
    public static function convertValue($type, $value)
    {
        switch ($type) {
            case DataEntryTypeProto::THIRD_PARTY_ATTRIBUTE:
                return ThirdPartyAttributeConverter::convertValue($value);
            default:
                error_log('Skipping unsupported data entry', 0);
                return null;
        }
    }
}
