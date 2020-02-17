<?php

declare(strict_types=1);

namespace Yoti\Profile\Util\ExtraData;

use Yoti\Exception\ExtraDataException;
use Yoti\Protobuf\Sharepubapi\DataEntry\Type as DataEntryTypeProto;

class DataEntryConverter
{
    /**
     * @param int $type
     * @param string $value
     *
     * @return mixed
     *
     * @throws \Yoti\Exception\ExtraDataException
     */
    public static function convertValue(int $type, string $value)
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
