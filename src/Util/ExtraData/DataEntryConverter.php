<?php

namespace Yoti\Util\ExtraData;

use Yoti\Exception\ExtraDataException;
use Yoti\Protobuf\Sharepubapi\DataEntry\Type as DataEntryTypeProto;

class DataEntryConverter
{
    /**
     * @param string $type
     * @param string $value
     *
     * @return \Yoti\Entity\AttributeIssuanceDetails
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    public static function convertValue($type, $value)
    {
        if (strlen($value) === 0) {
            throw new ExtraDataException('Value is empty');
        }

        switch ($type) {
            case DataEntryTypeProto::THIRD_PARTY_ATTRIBUTE:
                return ThirdPartyAttributeConverter::convertValue($value);
            default:
                throw new ExtraDataException("Unsupported data entry '{$type}'");
        }
    }
}
